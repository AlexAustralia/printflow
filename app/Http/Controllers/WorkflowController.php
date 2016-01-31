<?php

namespace App\Http\Controllers;

use App\QuoteRequest;
use App\Status;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use PhpSpec\Exception\Exception;

class WorkflowController extends Controller
{
    public function index()
    {
        $message = Session::get('message');
        $quote_requests = QuoteRequest::where('customer_id', '>', 0)
            ->where('status', '>', 0)->where('status', '<', 9)->get();

        $statuses = Status::all();

        $array = array();
        $i = 0;

        foreach($quote_requests as $quote_request)
        {
            $array[$i]['quote_number'] = $quote_request->id;
            $array[$i]['title'] = $quote_request->title;
            $array[$i]['description'] = $quote_request->summary;
            $array[$i]['artwork_image'] = $quote_request->artwork_image;
            $array[$i]['customer_id'] = $quote_request->customer_id;
            $array[$i]['customer_name'] = $quote_request->customer->customer_name;


            $array[$i]['request_date'] = $quote_request->request_date;
            $array[$i]['expiry_date'] = $quote_request->expiry_date;
            $array[$i]['status'] = $quote_request->get_status->value;
            $array[$i]['status_id'] = $quote_request->get_status->id;

            // Quotes
            if(isset($quote_request->get_quote->id)) {
                $array[$i]['supplier_id'] = $quote_request->get_quote->supplier->id;
                $array[$i]['supplier_name'] = $quote_request->get_quote->supplier->supplier_name;
            }
            else {
                $array[$i]['supplier_id'] = '';
                $array[$i]['supplier_name'] = '';
            }

            // Jobs
            if(isset($quote_request->job->id) && ($quote_request->quote_id != 0)) {
                $array[$i]['job_number'] = $quote_request->id;
                $array[$i]['quantity'] = $quote_request->job->job_item->quantity;
                $array[$i]['request_date'] = $quote_request->job->updated_at->format('d/m/Y');

                if ($array[$i]['status'] == 'New Job')
                {
                    // Working on the stages of a new job
                    $count = $quote_request->job->outside_work * 100 + $quote_request->job->design * 10 + $quote_request->job->on_proof;
                    switch ($count) {
                        case 0:
                            $array[$i]['stage'] = '';
                            break;
                        case 1:
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                        case 10:
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            break;
                        case 11:
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                        case 100:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            break;
                        case 101:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                        case 110:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            break;
                        case 111:
                            $array[$i]['stage'] = '<span class="label label-success">Outside Work</span>';
                            foreach ($array[$i] as $key => $item) {
                                $array[$i + 1][$key] = $array[$i][$key];
                                $array[$i + 2][$key] = $array[$i][$key];
                            }
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-primary">Design</span>';
                            $i++;
                            $array[$i]['stage'] = '<span class="label label-danger">On Proof</span>';
                            break;
                    }
                }
                else
                {
                    $array[$i]['stage'] = '';
                }
            }
            else {
                $array[$i]['job_number'] = '';
                $array[$i]['quantity'] = '';
                $array[$i]['stage'] = '';
            }

            $i++;
        }

        return view('workflow.index', compact('array', 'message', 'statuses'));
    }

    public function change_status($id, $status)
    {
        try {
            $quote_request = QuoteRequest::find($id);
            $quote_request->status = $status;
            $quote_request->save();

            return 'OK';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
