<?php

namespace App\Http\Controllers;

use App\CustomerAddress;
use App\Job;
use App\QuoteRequest;
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

        return redirect('job/'.$id.'/edit')->with('message', 'Job has been stored successfully');
    }


    // Delivery
    public function delivery_get($id, $page = 0)
    {
        $message = Session::get('message');

        $quote = QuoteRequest::find($id);
        $delivery_addresses = $quote->customer->delivery_addresses;

        return view('jobs.delivery', compact('quote', 'delivery_addresses', 'message', 'page'));
    }


    // Delivery Handler
    public function delivery_post(Request $request)
    {
        // Validate form
        $this->validate($request, [
            'delivery_date' => 'required',
            'cartons' => 'required|numeric',
            'items' => 'required|numeric',
            'delivery_address' => 'required'
        ]);

        $input = Input::all();
        //return $input;

        $quote = QuoteRequest::find($input['job_id']);
        $delivery_address = CustomerAddress::find($input['delivery_address']);

        // Create PDF
        if($input['value'] == 'sticker')
        {
            // Form Sticker
            $html = view('jobs.sticker', compact('input', 'quote', 'delivery_address'));
            $dompdf = PDF::loadHTML($html)->setPaper(array(0,0,422.37,283.49));
            //return $dompdf->stream();
            return $dompdf->download('sticker-'.$quote->id.'.pdf');
        }
        else
        {
            // Form Docket
            $html = view('jobs.docket', compact('input', 'quote', 'delivery_address'));
            $dompdf = PDF::loadHTML($html);
            //return $dompdf->stream();
            return $dompdf->download('docket-'.$quote->id.'.pdf');
        }
    }
}
