<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
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
        //$quotes = Quote::where('quote_request_id', '=', $qr_id)->get();

		return view('quotes.choose_suppliers', compact('quote_request'));

        //die("Sending RFQs for Quote Request ID: $qr_id");
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
        return view('quotes.enter_prices', compact('quote_request', 'quote'));
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

        return view('quotes.enter_prices', compact('quote_request', 'quote', 'quote_request_lines'));
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

        return view('quotes.evaluate', compact('quote_request', 'quote_request_lines'));
    }
    
    public function post_evaluate($qr_id){

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
    
        return view('quotes.evaluate', compact('quote_request', 'quantities'));

    }


    public function get_send_customer_quote($qr_id){
        $quote_request = QuoteRequest::Find($qr_id);

        return view('quotes.send_customer_quote', compact('quote_request'));
    }
    
    public function post_send_customer_quote($qr_id){

        $qr = QuoteRequest::Find($qr_id);

        
        /*
        $html = <<<EOT
        <html>
            <head>
                <style>
                    p {font-size:12px;}
                    #logo {font-family:\"adobe garamond pro\";}
                    #topInfo {font-family:\"helvetica\";}
                    #mainContent {position:relative;top:-150px;}
                    #address {
                        position:relative;
                        top:95px;
                        left:60px;
                        font-size:16px;
                        margin-bottom:100px;
                    }
                </style>
            </head>
            <body>
                <div class="heightMarker" style="position:absolute;top:28.59%;left:-25px;"><p>.</p></div>

                <div class="heightMarker" style="position:absolute;top:66.6%;left:-25px;"><p>.</p></div>

                <div id="header">
                    <div id="logo" style="width:260px;position:relative;top:-50px;color:rgb(36,53,136);">
                        <p>
                            <div style="font-size:80px;text-align:right;">
                                Franklin
                            </div>
                            <div style="font-size:40px;text-align:right;position:relative;top:-40px;">
                                Direct
                            </div>
                        </p>
                    </div>

                    <div id="topInfo" style="position:fixed;top:25px;left:500px;width:250px;">
                        <p style="font-size:16px;color:rgb(36,53,136);"><i>Your Business. Our Business.</i></p>
                        <p style="font-size:16px;color:rgb(116,175,39);position:relative;top:-10px;"><i>Your Planet. Our Planet.</i></p>
                    </div>

                    <table style="position:fixed;top:145px;left:500px;border-collapse:collapse;">
                        <tr style="outline:thin solid grey;">
                            <td width="130px">Quotation:</td>
                            <td>$quote_id</td>
                        </tr>
                        <tr style="border:1px solid grey;">
                            <td>Date:</td>
                            <td>$date</td>
                        </tr>
                        <tr style="outline:thin solid red;">
                            <td>Quote Valid:</td>
                            <td>30 days</td>
                        </tr>
                    </table>
                </div>

                <div id="mainContent">
                    <p id="address">
                    <b>$address</p>
                    <br />
                    <div style="display:inline;font-size:16px;">
                        <p style="font-size:16px;">
                            Dear $first_name<br />
                            Thank you for the opportunity to quote on this job. We are pleased to submit the following quotation.
                        </p>
                        <p style="font-size:16px;">
                            <b>Title: </b>$title
                        </p>
                    </div>
                    $job_info
                    <br />
                    <br />
                    $prices
                </div>

                <div id="footer">
                    <div id="leftFoot" style="position:fixed;bottom:120px;width:50%;">
                        <p><b>Terms and Conditions</b></p>
                        <p>This quote is subject to the following terms and conditions.  Any additional artwork needed can be quoted.</p>
                        <p>To accept this quote, please email: sales@franklindirect.com.au, with  quote number and quantity required.</p>
                    </div>
                    <div id="rightFoot" style="position:fixed;bottom:120px;left:60%;width:40%;">
                        <p>
                            <b>Franklin Press trading as Franklin Direct</b><br />
                            ABN:82 009 574 387
                        </p>
                        <p>
                            91 Albert Road <br />
                            Moonah, Tasmania 7009
                        </p>
                        <p>
                            P:(03) 6228 6130 <br />
                            F:(03) 6228 6340 <br />
                            E:sales@franklindirect.com.au<br />
                            www.franklindirect.com.au
                        </p>
                    </div>
                </div>

                <div style="page-break-after:always;">
                </div>

                <div style="background-image:url(images/Background2.jpg);width:100%;height:100%;">
                </div>
            </body>
        </html>
EOT;
        */

        $address = str_replace("\n", "<br />", $qr->customer->postal_address());

        $cust = $qr->customer;
        $contacts = $cust->customer_contacts;
        if (sizeof($contacts) < 1 || $contacts[0]->first_name == ""){
            $first_name = $cust->customer_name;
        } else {
            $first_name = $contacts[0]->first_name;
        }

        $title = $qr->title;
        $job_info = str_replace("\n", "<br />", $qr->summary);
        $prices = "<table border='1'>";
        $prices .= "<tr><td>Qty</td><td>Price</td><td>GST</td><td>Total</td><td>Unit Price</td></tr>";
        foreach ($qr->qris as $qri) {
            $qty = $qri->quantity;
            $price = $qri->price;
            $gst = $qri->gst;
            $total = $qri->total;
            $unit_price = $qri->unit_price;
            
            $prices .= "<tr><td><td>$qty</td><td>$price</td><td>$gst</td><td>$total</td><td>$unit_price</td></tr>";
        }        
        $prices .= "</table>";

        $html = <<<EOT
        <p>Quote Request ID: $qr->id</p>
        <p>Date: $qr->request_date</p>

        <p>Address:</p>
        <p>$address</p>

        <p>First Name: $first_name</p>
        <p>Title: $title</p>

        <p>Job Info:</p>
        <p>$job_info</p>

        <p>Prices:</p>
        $prices
EOT;
    
        $dompdf = PDF::loadHTML($html)->save('../public/quotes/'.$qr->id.'.pdf');

        echo "<a href='/quotes/$qr->id.pdf'>Quote PDF here</a>";

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
