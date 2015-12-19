<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\QuoteItem;
use App\Supplier;
use App\QuoteRequest;
use App\QuoteRequestItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Input;
use Intervention\Image\Facades\Image;

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
        $quote_requests = QuoteRequest::where('customer_id', '>', 0)->get();
        return view('quote_requests.index', compact('quote_requests'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //$supplier_lists = Supplier::lists('supplier_name', 'id');
		//return view('quote_requests.create');
        $qr = QuoteRequest::firstOrCreate(['customer_id' => 0]);
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
        $message = Session::get('message');
        $q = QuoteRequest::find($id);
        $quote_request = $q;
		return view('quote_requests.edit', compact('q', 'quote_request' ,'message'));
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

		$qri_input = array_where($all, function($key, $value){
            return starts_with($key, 'qri');
        });

        $qri_id_storing = array();
        foreach ($qri_input['qri_id'] as $item)
        {
            array_push($qri_id_storing, $item);
        }

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

            $qri["description"] = $qr_input["title"];

            if ($qri_id == ""){
                // create new qri

                // first check if anything has been entered
                if (($qri["quantity"] != "" &&
                    $qri["quantity"] > 0 )){
                        QuoteRequestItem::create($qri);
                }
            } else {
                // update old qri
                $item = QuoteRequestItem::find($qri_id);
                if($qri["quantity"] == "" || is_numeric($qri["quantity"]))
                $item->update($qri);
            }
        }

        // Get stored quote lines for delete checking
        $quotes_lines = QuoteRequestItem::where('quote_request_id', $id)->get();
        $quote_request = QuoteRequest::find($id);
        $quotes = $quote_request->quotes;

        // At first delete quote items related to chosen item
        foreach($quotes as $quote)
        {
            foreach($quotes_lines as $quotes_line) {
                if(($quotes_line->quantity == 0) || ($quotes_line->quantity == ''))
                {
                    $quote_items = QuoteItem::where('quote_id', $quote->id)->where('qri_id', $quotes_line->id);
                    $quote_items->delete();
                }
            }
        }

        // Delete chosen items
        foreach ($quotes_lines as $quotes_line) {
            if (($quotes_line->quantity == 0) || ($quotes_line->quantity == ''))
            {
                $quote_line_delete = QuoteRequestItem::find($quotes_line->id);
                $quote_line_delete->delete();
            }
        }

        // process other input (quote request data)
        $q = QuoteRequest::find($id);
        $q->update($qr_input);

        // Storing artwork image
        if (Input::hasFile('artwork'))
        {
            if (Input::file('artwork')->isValid())
            {
                $extension = Input::file('artwork')->getClientOriginalExtension();
                $file_name = $id.'.'.$extension;
                $destination_path = 'uploads/artworks';

                Input::file('artwork')->move($destination_path, $file_name);

                // Make a thumbnail picture
                $thumbnail = Image::make($destination_path.'/'.$file_name);
                $thumbnail->resize(55, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $thumbnail->save('uploads/thumbnails/'.$file_name);

                $q->artwork_image = $file_name;
                $q->save();
            }
        }

        // delete Quote PDF if exists
        $path = 'quotes/'.$id.'.pdf';
        if (file_exists($path))
        {
            unlink($path);
        }

        // Reset chosen supplier for quotes
        $q->quote_id = 0;
        $q->save();

        return redirect()->route('quote_requests.edit', $id)->with('message', 'Quote Request has been Updated');
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
