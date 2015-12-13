<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Request;
use Mail;
use App\helpers;

use App\Quote;
use App\QuoteItem;
use App\QuoteRequest;
use PDF;

class QuotesController extends Controller {

    public function get_choose_suppliers($qr_id)
    {
        $quote_request = QuoteRequest::Find($qr_id);

		return view('quotes.choose_suppliers', compact('quote_request'));
    }


    public function post_choose_suppliers(\Illuminate\Http\Request $request, $qr_id)
    {
        $quote_request = QuoteRequest::Find($qr_id);
        $input = Request::all();

        $action = $input['submit'];

        if ($action == "Add"){

            // Validate form
            $this->validate($request, [
                'supplier' => 'required',
                'supplier_id' => 'required|not_in:0',
            ], $messages = array(
                'supplier.required' => 'You should enter a Supplier Name',
                'supplier_id.not_in' => 'You should choose a valid Supplier'
            ));

            $supplier_id = $input['supplier_id'];

            if($supplier_id > 0)
            {
                // At first, we check if this supplier already in DB
                $added_suppliers = array();
                foreach ($quote_request->quotes as $quotes)
                {
                   array_push($added_suppliers, $quotes->supplier_id);
                }

                if(!in_array($supplier_id, $added_suppliers)) {
                    $quote_request->quotes()->save(new Quote($input));
                }
            }

        } elseif ($action == "Remove") {
            if(isset($input['suppliers']))
            {
                $supplier_id = $input['suppliers'];

                $quote = $quote_request->quotes()->where('supplier_id', '=', $supplier_id);

                $quote_items = QuoteItem::where('quote_id', $quote->first()->id);
                $quote_items->delete();

                $quote->delete();
            }
        }

        return redirect('choose_suppliers/'.$qr_id);
    }
    

    public function get_send_rfq_emails($qr_id)
    {
        $quote_request = QuoteRequest::Find($qr_id);
        $user = Auth::user();

        return view('quotes.send_rfq_emails', compact('quote_request', 'user'));
    }
    

    public function post_send_rfq_emails($qr_id)
    {
        $quote_request = QuoteRequest::Find($qr_id);
        $input = request::all();

        $from = $input['from'];
        $replyto = $input['reply-to'];
        $bcc = $input['bcc'];
        $subject = $input['subject'];
        $body = $input['body'];


        // Split up combined text bcc addresses into correct pairs
        $combined_bccs = explode(',', $bcc);
        $headers = "";
        foreach($combined_bccs as $combined){
            $matches = [];
            $pattern = "/(.*?)\<(.*?)\>/";
            preg_match($pattern, $combined, $matches);
            if (count($matches)>=3){
                $headers .= "Bcc: ".$matches[2]."\r\n";
            } else {
                $headers .= "Bcc: ".$combined."\r\n";
            }
        }

        $headers .= "From: ".$from."\r\n";
        $headers .= "Reply-to: ".$replyto."\r\n";

        // Send_email
        if (!mail($replyto, $subject, $body, $headers)){
            $quote_request = QuoteRequest::Find($qr_id);
            $user = Auth::user();
            $message = "Mail has not been sent due to some errors";

            return view('quotes.send_rfq_emails', compact('quote_request', 'user', 'message'));
        }

        $quote = $quote_request->first_quote();
        $quote_request_lines = $quote_request->qris;

        return view('quotes.enter_prices', compact('quote_request', 'quote', 'quote_request_lines'));
    }


    public function get_enter_prices($qr_id, $qid=null){
        $quote_request = QuoteRequest::Find($qr_id);

        if ($qid == null){
            $quote = $quote_request->first_quote();
        } else {
            $quote = Quote::Find($qid);
        }

        $quote_request_lines = $quote_request->qris;

        return view('quotes.enter_prices', compact('quote_request', 'quote', 'quote_request_lines'));
    }
    
    public function post_enter_prices($qr_id, $qid=null){
        $quote_request = QuoteRequest::Find($qr_id);
        if ($qid == null){
            $quote = $quote_request->first_quote();
        } else {
            $quote = Quote::Find($qid);
        }

        $input = request::all();
        

        // Invert form array
        $input = array_except($input, ['_token', 'quote_request_id', 'qid']);
        $keys = array_keys($input);
        $first_key = $keys[0];
        $count = count($input[$first_key]);
        $output = [];

        foreach (range(0, $count-1) as $i){
            foreach ($keys as $key){
                $output[$i][$key] = $input[$key][$i];
            }
        }
        foreach ($output as $item) {
            $id = $item["id"];
            if ($id == ""){ // Create new
                QuoteItem::create($item);
            } else {
                $qi = QuoteItem::find($id);
                $qi->update($item);
            }
        }

        $quote_request_lines = $quote_request->qris;

        return view('quotes.enter_prices', compact('quote_request', 'quote', 'quote_request_lines'))->with('message', 'Supplier Prices have been Updated');
    }


    // Evaluate Prices


    public function get_evaluate($qr_id){
        $quote_request = QuoteRequest::Find($qr_id);
        
        $quote = $quote_request->first_quote();

        if (isset($quote)) {
            //$quantities = $quote->quantities();
            $quote_request_lines = $quote_request->qris;
        } else {
            //$quantities = [];
            $quote_request_lines = [];
        }

        // Check if supplier prices was entered
        $error = false;
        foreach($quote_request->quotes as $quote)
        {
            foreach($quote->quote_items() as $quote_item)
            {
                if($quote_item == null) $error = true;
            }

        }

        return view('quotes.evaluate', compact('quote_request', 'quote_request_lines', 'error'));
    }
    
    public function post_evaluate(\Illuminate\Http\Request $request, $qr_id){

        // Validate form
        $this->validate($request, [
            'quote_id' => 'required',
        ], $messages = array(
            'quote_id.required' => 'You should choose a Supplier for creating a Quote'
        ));

        $quote_request = QuoteRequest::find($qr_id);
        $quantities = $quote_request->first_quote()->quantities();

        $input = request::all();
        $quote_id = $input['quote_id'];

        $quote = Quote::find($quote_id);

        //echo "<pre>";
        //echo "Selecting Quote ID $quote_id for Quote Request $qr_id\n";

        foreach ($quote_request->qris as $qri){
            $qty = $qri["quantity"];
            $qi = QuoteItem::where("quantity", "=", $qty)
                           ->where("quote_id", "=", $quote_id)
                           ->first();
            
            //print("QRI: " . $qri["quantity"] . ": " . $qri["price"]."\n");

            if ($qi == null){
                //print("Could not find quote item for Quantity $qty\n");
                $qri["price"] = 0;
                $qri["gst"] = 0;
                $qri["total"] = 0;
                $qri["unit_price"] = 0;
                $qri->save();

            } else {
                $qri["price"] = $qi["total_net"];
                $qri["gst"] = $qri["price"]*0.1;
                $qri["total"] = $qri["price"] + $qri["gst"];
                $qri["unit_price"] = $qri["total"] / $qri["quantity"];
                $qri->save();
            }
        }

        $quote_request = QuoteRequest::find($qr_id);
        $quote_request->quote_id = $input['quote_id'];
        $quote_request->save();

        return redirect('evaluate/'.$qr_id);
    }


    public function get_send_customer_quote($qr_id){
        $message = Session::get('message');

        $quote_request = QuoteRequest::Find($qr_id);
        $qris = $quote_request->qris;

        return view('quotes.send_customer_quote', compact('quote_request' ,'qris', 'message'));
    }
    
    public function post_send_customer_quote(\Illuminate\Http\Request $request, $qr_id)
    {
        $input = Request::all();

        $qr = QuoteRequest::Find($qr_id);
        $customer = $qr->customer;
        $qris = $qr->qris;

        if($input['submit'] == 'Create PDF')
        {
            // Creating PDF Quote
            $html = view('quotes.pdf', compact('qr', 'customer', 'qris'));
            $dompdf = PDF::loadHTML($html)->save('../public/quotes/'.$qr->id.'.pdf');

            return redirect('send_customer_quote/'.$qr_id)->with('message', 'PDF Quote has been successfully created');
        }
        else
        {
            // Sending email
            $user = Auth::user();
            $contacts = $customer->customer_contacts;

            $from = $user->email;
            $replyto = $from;

            $emails = array();
            foreach($contacts as $contact)
            {
                array_push($emails, $contact->email);
            }

            //$bcc = implode(', ', $emails);
            $subject = 'Quote';
            //$body = $input['body'];

            // Split up combined text bcc addresses into correct pairs
            $combined_bccs = $emails;
            $headers = "";
            foreach($combined_bccs as $combined){
                $matches = [];
                $pattern = "/(.*?)\<(.*?)\>/";
                preg_match($pattern, $combined, $matches);
                if (count($matches)>=3){
                    $headers .= "Bcc: ".$matches[2]."\r\n";
                } else {
                    $headers .= "Bcc: ".$combined."\r\n";
                }
            }

            $headers .= "From: ".$from."\r\n";
            $headers .= "Reply-to: ".$replyto."\r\n";

            $path = 'quotes/18.pdf';
            $html = 'Test message';

            if ($path)
            {
                $fp = fopen($path,"rb");
                $file = fread($fp, filesize($path));
                fclose($fp);
            }
            $name = basename($path);
            $EOL = "\r\n";
            $boundary     = "--".md5(uniqid(time()));
            $headers    = "MIME-Version: 1.0;$EOL";
            $headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";
            $headers   .= "From: Franklin Direct <$from>";

            $multipart  = "--$boundary$EOL";
            $multipart .= "Content-Type: text/html;$EOL";
            $multipart .= "Content-Transfer-Encoding: base64$EOL";
            $multipart .= $EOL;
            $multipart .= chunk_split(base64_encode($html));
            $multipart .=  "$EOL--$boundary$EOL";
            if (file_exists($path))
            {
                $multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
                $multipart .= "Content-Transfer-Encoding: base64$EOL";
                $multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";
                $multipart .= $EOL;
                $multipart .= chunk_split(base64_encode($file));
                $multipart .= "$EOL--$boundary--$EOL";
            }

            $mail_to = 'rezultat-ltd@mail.ru';
                $thema = 'Quote';
            if(!mail($mail_to, $thema, $multipart, $headers))
            {
                echo 'Error';
            }
            else
            {
                echo 'OK';
            }

            return;
            // Send_email
            if (!mail($replyto, $subject, $body, $headers)){
                $quote_request = QuoteRequest::Find($qr_id);
                $user = Auth::user();
                $message = "Mail has not been sent due to some errors";

                return view('quotes.send_rfq_emails', compact('quote_request', 'user', 'message'));
            }
        }

    }


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
