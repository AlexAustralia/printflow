<?php namespace App\Http\Controllers;

use App\ArtworkCharge;
use App\Freight;
use App\FreightItem;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Term;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
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

                $quote_request->quote_id = 0;
                $quote_request->save();

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

        //Update status
        $quote_request->status = 2;
        $quote_request->save();

        return redirect('/enter_prices/'.$qr_id);
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

        // Reset chosen supplier for quotes
        $quote_request->quote_id = 0;
        $quote_request->save();

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

        // delete Quote PDF if exists
        $path = 'quotes/'.$qr_id.'.pdf';
        if (file_exists($path))
        {
            unlink($path);
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

        // delete Quote PDF if exists
        $path = 'quotes/'.$qr_id.'.pdf';
        if (file_exists($path))
        {
            unlink($path);
        }

        return redirect('evaluate/'.$qr_id);
    }


    public function get_send_customer_quote($qr_id){
        $message = Session::get('message');
        $error = Session::get('error');

        $quote_request = QuoteRequest::Find($qr_id);
        $qris = count($quote_request->get_quote) > 0 ? $quote_request->get_quote->qris : [];

        $terms = Term::all();

        return view('quotes.send_customer_quote', compact('quote_request' ,'qris', 'message', 'error', 'terms'));
    }


    public function post_send_customer_quote(\Illuminate\Http\Request $request, $qr_id)
    {
        $input = Request::all();

        $qr = QuoteRequest::Find($qr_id);
        $customer = $qr->customer;
        $qris = $qr->get_quote->qris;

        if ($input['term_id'] > 0) {
            $qr->terms_id = $input['term_id'];
        } else {
            $qr->terms_id = null;
        }
        $qr->save();

        $terms = $qr->terms;

        if($input['submit'] == 'Create PDF')
        {
            // Creating PDF Quote
            $html = view('quotes.pdf', compact('qr', 'customer', 'qris', 'terms'));
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

            $mail_to = array();
            foreach($contacts as $contact)
            {
                array_push($mail_to, $contact->email);
            }

            $mail_to = implode(', ', $mail_to);

            $subject = 'Quote';
            $body = '<p>Dear '.$customer->postal_attention.',</p><p>Please find enclosed a quote for your order.</p>'.
                    '<p>Kind Regards,<br>'.$user->name.' at Franklin Direct</p>';

            $path = 'quotes/'.$qr_id.'.pdf';

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
            $multipart .= chunk_split(base64_encode($body));
            $multipart .=  "$EOL--$boundary$EOL";

            if (file_exists($path))
            {
                $fp = fopen($path,"rb");
                $file = fread($fp, filesize($path));
                fclose($fp);

                $multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";
                $multipart .= "Content-Transfer-Encoding: base64$EOL";
                $multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";
                $multipart .= $EOL;
                $multipart .= chunk_split(base64_encode($file));
                $multipart .= "$EOL--$boundary--$EOL";
            }

            // Send_email
            if(!mail($mail_to, $subject, $multipart, $headers))
            {
                return redirect('send_customer_quote/'.$qr_id)->with('error', 'Mail has not been sent due to some errors');
            }
            else
            {
                // Update status
                $qr->status = 3;
                $qr->save();

                return redirect('send_customer_quote/'.$qr_id)->with('message', 'Mail has been sent successfully');
            }
        }
    }


    public function get_artwork($id)
    {
        $quote_request = QuoteRequest::Find($id);
        $artwork_charges = $quote_request->artwork_charges;

        $message = Session::get('message');

        return view('quotes.artwork', compact('quote_request', 'artwork_charges', 'message'));
    }


    public function post_artwork($id)
    {
        $input = Input::all();

        $quoute_request = QuoteRequest::find($id);
        $artwork_lines = $quoute_request->artwork_charges;

        $artwork_lines_to_delete = array();

        foreach($artwork_lines as $artwork_line) {
            array_push($artwork_lines_to_delete, $artwork_line->id);
        }

        if(isset($input['artwork'])) {
            foreach($input['artwork'] as $index => $line) {
                if (isset($line['id'])) {
                    $artwork = ArtworkCharge::find($line['id']);

                    // Delete this item from array for deleting
                    foreach ($artwork_lines_to_delete as $key => $item) {
                        if ($item == $artwork->id) {
                            unset($artwork_lines_to_delete[$key]);
                        }
                    }
                } else {
                    $artwork = new ArtworkCharge();
                }

                $artwork->quote_request_id = $quoute_request->id;
                $artwork->description = $line['description'];
                $artwork->supplier_id = $line['supplier_id'];
                $artwork->hours = is_numeric($line['hours']) ? $line['hours'] : NULL;
                $artwork->rate = is_numeric($line['rate']) ? $line['rate'] : NULL;
                $artwork->total_cost = $line['total_cost'];
                $artwork->markup = is_numeric($line['markup']) ? $line['markup'] : 0;
                $artwork->total = $line['total'];

                $artwork->save();

                // Erase ticked files
                if(isset($line['erase_files'])) {
                    $file_names_array = is_null($artwork->files) ? [] : unserialize($artwork->files);

                    foreach ($line['erase_files'] as $erase_file_index) {
                        $path = 'uploads/attachments/' . $file_names_array[$erase_file_index];

                        if (file_exists($path)) {
                            unlink($path);
                        }

                        unset($file_names_array[$erase_file_index]);
                    }

                    $artwork->files = serialize($file_names_array);
                    $artwork->save();
                }

                // Storing artwork files
                if (Input::hasFile('files-'.$index))
                {
                    $files = Input::file('files-'.$index);
                    $file_names_array = is_null($artwork->files) ? [] : unserialize($artwork->files);

                    foreach ($files as $file)
                    {
                        if ($file->isValid()) {
                            $filename = $file->getClientOriginalName();
                            $destination_path = 'uploads/attachments';

                            $file->move($destination_path, $filename);

                            if (!in_array($filename, $file_names_array))
                                array_push($file_names_array, $filename);
                        }
                    }

                    $artwork->files = serialize($file_names_array);
                    $artwork->save();
                }
            }
        }

        // Delete needed items
        foreach ($artwork_lines_to_delete as $line){
            $artwork = ArtworkCharge::find($line);

            $file_names_array = is_null($artwork->files) ? [] : unserialize($artwork->files);

            foreach ($file_names_array as $file) {
                $path = 'uploads/attachments/' . $file;

                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        ArtworkCharge::destroy($artwork_lines_to_delete);

        $quoute_request->artwork_charge = $input['artwork_charge'];

        $quoute_request->save();

        return redirect('artwork/'.$id)->with('message', 'Artwork charges have been saved successfully');
    }


    public function get_freight($id)
    {
        $quote_request = QuoteRequest::Find($id);
        $freights = $quote_request->freight_charges;

        $message = Session::get('message');

        return view('quotes.freight', compact('quote_request', 'freights', 'message'));
    }


    public function post_freight($id)
    {
        $input = Input::all();

        $quoute_request = QuoteRequest::find($id);
        $freight_lines = $quoute_request->freight_charges;

        $freight_lines_to_delete = array();

        foreach($freight_lines as $freight_line) {
            array_push($freight_lines_to_delete, $freight_line->id);
        }

        $freight_charge = [];

        foreach ($quoute_request->qris as $line) {
            array_push($freight_charge, 0);
        }

        // If Freight data was entered
        if(isset($input['freight'])) {

            foreach($input['freight'] as $index => $line) {

                if (isset($line['freight_id'])) {
                    $freight = Freight::find($line['freight_id']);

                    // Delete this item from array for deleting
                    foreach ($freight_lines_to_delete as $key => $item) {
                        if ($item == $freight->id) {
                            unset($freight_lines_to_delete[$key]);
                        }
                    }
                } else {
                    $freight = new Freight();
                }

                $freight->quote_request_id = $quoute_request->id;
                $freight->type = $line['type'];

                if (isset($line['freight_id_chosen'])) {
                    $freight->include_in_quote = true;
                }
                else {
                    $freight->include_in_quote = false;
                }

                $freight->save();

                // Save freight items fields
                foreach($line['qri_id'] as $key => $column) {
                    if(isset($line['id'])) {
                        $freight_item = FreightItem::find($line['id'][$key]);
                    }
                    else {
                        $freight_item = new FreightItem();
                    }

                    $freight_item->freight_id = $freight->id;
                    $freight_item->qri_id = $line['qri_id'][$key];
                    $freight_item->supplier_id = $line['supplier_id'][$key];
                    $freight_item->cbm = isset($line['cbm']) ? $line['cbm'][$key] : null;
                    $freight_item->cbm_rate = isset($line['cbm_rate']) ? $line['cbm_rate'][$key] : null;
                    $freight_item->number_items = isset($line['number_items']) ? $line['number_items'][$key] : null;
                    $freight_item->fixed_cost = isset($line['fixed_cost']) ? $line['fixed_cost'][$key] : null;
                    $freight_item->total_cost = $line['total_cost'][$key];
                    $freight_item->markup = $line['markup'][$key];
                    $freight_item->total = $line['total'][$key];

                    $freight_item->save();

                    if (isset($line['freight_id_chosen'])) {
                        $freight_charge[$key] += $freight_item->total;
                    }
                }


                // Erase ticked files
                if(isset($line['erase_files'])) {
                    $file_names_array = is_null($freight->files) ? [] : unserialize($freight->files);

                    foreach ($line['erase_files'] as $erase_file_index) {
                        $path = 'uploads/attachments/' . $file_names_array[$erase_file_index];

                        if (file_exists($path)) {
                            unlink($path);
                        }

                        unset($file_names_array[$erase_file_index]);
                    }

                    $freight->files = serialize($file_names_array);
                    $freight->save();
                }

                // Storing freight files
                if (Input::hasFile('files-'.$index))
                {
                    $files = Input::file('files-'.$index);
                    $file_names_array = is_null($freight->files) ? [] : unserialize($freight->files);

                    foreach ($files as $file)
                    {
                        if ($file->isValid()) {
                            $filename = $file->getClientOriginalName();
                            $destination_path = 'uploads/attachments';

                            $file->move($destination_path, $filename);

                            if (!in_array($filename, $file_names_array))
                                array_push($file_names_array, $filename);
                        }
                    }

                    $freight->files = serialize($file_names_array);
                    $freight->save();
                }
            }
        }

        // Save freight charges
        foreach ($quoute_request->qris as $key => $line) {
            $line->freight_charge = $freight_charge[$key];
            $line->save();
        }

        // Delete needed items
        foreach ($freight_lines_to_delete as $line){
            $freight = Freight::find($line);

            $file_names_array = is_null($freight->files) ? [] : unserialize($freight->files);

            foreach ($file_names_array as $file) {
                $path = 'uploads/attachments/' . $file;

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Delete related freight_items
            $freight_items = $freight->freight_items;

            foreach($freight_items as $freight_item) {
                $freight_item->delete();
            }
        }

        Freight::destroy($freight_lines_to_delete);

        return redirect('freight/'.$id)->with('message', 'Freight charges have been saved successfully');
    }
}
