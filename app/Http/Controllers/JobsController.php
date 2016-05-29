<?php

namespace App\Http\Controllers;

use App\CustomerAddress;
use App\DeliveryHistory;
use App\Job;
use App\QuoteRequest;
use Illuminate\Support\Collection;
use PDF;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class JobsController extends Controller
{
    // Creating a job from a quote
    public function edit($id)
    {
        $message = Session::get('message');

        $quote_request = QuoteRequest::find($id);
        $job = Job::where('quote_requests_id', $id)->first();
        $qris = $quote_request->qris;

        return view('jobs.edit', compact('quote_request', 'qris', 'job', 'message'));
    }


    // Save created job
    public function save(Request $request, $id)
    {
        // Validate form
        $this->validate($request, [
            'quote_request_items_id' => 'required',
        ], $messages = array(
            'quote_request_items_id.required' => 'You should choose a quantity to proceed with'
        ));

        $input = Input::all();

        $job = Job::firstOrNew(['quote_requests_id' => $id]);

        $job->quote_requests_id = $id;
        $job->quote_request_items_id = $input['quote_request_items_id'];

        $job->save();

        // Update status
        $quote_request = QuoteRequest::find($id);
        $quote_request->status = 4;
        $quote_request->save();

        return redirect('job/'.$id.'/edit')->with('message', 'Job has been stored successfully');
    }


    // Delivery
    public function delivery_get($id)
    {
        $message = Session::get('message');

        $quote = QuoteRequest::find($id);
        $delivery_addresses = $quote->customer->delivery_addresses;
        $delivery_history = DeliveryHistory::select('id', 'number', 'delivery_date', 'input')->where('qr_id', $id)->get();

        $page = isset($message) ? 'history' : 'delivery';

        return view('jobs.delivery', compact('quote', 'delivery_addresses', 'message', 'delivery_history', 'page'));
    }


    // Delivery Handler
    public function delivery_post(Request $request)
    {
        // Validate form
        $this->validate($request, [
            'delivery_date' => 'required',
            'delivery_address' => 'required'
        ]);

        $input = Input::all();

        $quote = QuoteRequest::find($input['job_id']);
        $delivery_addresses = $quote->customer->delivery_addresses;

        // Store generated Delivery
        $store_input = $input;
        unset($store_input['_token']);
        unset($store_input['value']);

        $previous_delivery = DeliveryHistory::select('id')->where('qr_id', $input['job_id'])->get();

        $delivery_history = new DeliveryHistory();

        $delivery_history->number = count($previous_delivery) > 0 ? $input['job_id'].'-'.count($previous_delivery) : $input['job_id'];
        $delivery_history->delivery_date = $input['delivery_date'];
        $delivery_history->qr_id = $input['job_id'];
        $delivery_history->input = serialize($store_input);

        $delivery_history->save();

        return redirect('/job/'.$input['job_id'].'/delivery')->with('message', 'Delivery Docket and Sticker have been created successfully');
    }

    // Display PDF Docket
    public function show_docket($id)
    {
        $delivery_history = DeliveryHistory::find($id);
        $input = unserialize($delivery_history->input);

        $quote = QuoteRequest::find($delivery_history->qr_id);
        $delivery_address = CustomerAddress::find($input['delivery_address']);

        // Create PDF
        $qtys = isset($input['number']) ? $input['number'] : '';

        if (isset($input['number'])) {
            // Quantities are not equal
            $collection = Collection::make($input['number']);
            $numbers = $collection->flip()->map(function() {
                return 0;
            });

            foreach ($collection as $item) {
                $numbers[$item] += 1;
            }

            $html = view('jobs.docket', compact('input', 'quote', 'delivery_address', 'qtys', 'numbers'));
        } else {
            // Quantities are equal
            $html = view('jobs.docket', compact('input', 'quote', 'delivery_address', 'qtys'));
        }

        $dompdf = PDF::loadHTML($html);

        return $dompdf->stream();
    }

    // Display PDF Sticker
    public function show_sticker($id)
    {
        $delivery_history = DeliveryHistory::find($id);
        $input = unserialize($delivery_history->input);

        $quote = QuoteRequest::find($delivery_history->qr_id);
        $delivery_address = CustomerAddress::find($input['delivery_address']);

        // Create PDF
        $qtys = isset($input['number']) ? $input['number'] : '';

        $html = view('jobs.sticker', compact('input', 'quote', 'delivery_address', 'qtys'));
        $dompdf = PDF::loadHTML($html)->setPaper(array(0,0,422.37,283.49));

        return $dompdf->stream();
    }
}
