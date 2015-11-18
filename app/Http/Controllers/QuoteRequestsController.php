<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\QuoteRequest;
use App\QuoteRequestItem;

use Illuminate\Http\Request;
use Input;

class QuoteRequestsController extends Controller {

    public function __construct(){
        $this->middleware('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $quote_requests = QuoteRequest::All();
        return view('quote_requests.index', compact('quote_requests'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        #$supplier_lists = Supplier::lists('supplier_name', 'id');
		#return view('quote_requests.create', compact('supplier_lists'));
        $qr = QuoteRequest::create([]);
        return redirect()->route('quote_requests.edit', $qr["id"]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
        QuoteRequest::create($input);
        return redirect()->route('quote_requests.index')->with('message', 'Quote Request Created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(QuoteRequest $req)
	{
		return view('quote_requests.show', compact('req'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $q = QuoteRequest::find($id);
        $quote_request = $q;
		return view('quote_requests.edit', compact('q', 'quote_request'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        // Separate out the incoming data
        $all = Input::all();

        $qri_deletes = array_pull($all, 'qri_delete'); 
        if ($qri_deletes === null){
            $qri_deletes = [];
        };

		$qri_input = array_where($all, function($key, $value){
            return starts_with($key, 'qri');
        });

        $qr_input = array_where($all, function($key, $value){
            return !starts_with($key, 'qri') 
                    && $key != '_method'
                    && $key != 'customer';
        });


        // invert qri_input array
        $input = $qri_input;
        
        $keys = array_keys($input);
        $first_key = $keys[0];
        $count = count($input[$first_key]);
        $output = [];

        foreach (range(0, $count-1) as $i){
            foreach ($keys as $key){
                $output[$i][$key] = $input[$key][$i];
            }
        }
        $qri_input = $output;

        // remove qri_ prefix from array keys
        $prefix = "qri_";
        $output = [];
        foreach ($qri_input as $qri){
            foreach (array_keys($qri) as $k){
                if (starts_with($k, $prefix)){
                    $nk = substr($k, strlen($prefix));
                    $qri[$nk] = $qri[$k];
                    unset($qri[$k]);
                }
            }
            $output[] = $qri;
        }
        $qri_input = $output;


        // process inverted qri_input array
        foreach ($qri_input as $qri){
            $qri_id = array_pull($qri, 'id');

            if ($qri["description"] == "") {
                $qri["description"] = $qr_input["title"];
            }

            if ($qri_id == ""){
                // create new qri

                // first check if anything has been entered
                if (!($qri["quantity"] == "" &&
                    $qri["price"] == "" &&
                    $qri["gst"] == "" &&
                    $qri["total"] == "" &&
                    $qri["unit_price"] == "")){
                        QuoteRequestItem::create($qri);
                }
            } else {
                // update old qri
                $item = QuoteRequestItem::find($qri_id);
                $item->update($qri);
            }
        }

        // process qri_deletes
        foreach ($qri_deletes as $qri_id){
            $item = QuoteRequestItem::find($qri_id);
            $item->delete();
        }

        
        // process other input (quote request data)
        $q = QuoteRequest::find($id);
        $q->update($qr_input);
        return redirect()->route('quote_requests.edit', $id)->with('message', 'Quote Request Updated');
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
