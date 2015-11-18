<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Supplier;
use App\SupplierContact;

use URL;
use Input;
use Debugbar;

class SuppliersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$suppliers = Supplier::all()->sortBy('supplier_name');
		return view('suppliers.index', compact('suppliers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$supplier = new Supplier;
		$contacts = $supplier->supplier_contacts;
		return view('suppliers.create', compact('supplier', 'contacts'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

	    $this->validate($request, [
	        'supplier_name' => 'required|unique:suppliers',
	        // 'body' => 'required',
	    ]);



	    //store input to session
	    Input::flash();

	    //create customer
        $result = Supplier::create( Input::all() );

        if ( ! $result->id ) {
			return redirect()->route('suppliers.create')->with('message', 'Unable to create supplier, please try again.');
        }

        //redirect after successful save to customers.edit
	    return redirect()->route('suppliers.edit', $result->id)->with('message', 'Supplier created!')->withInput();

	    //DEBUG
	    // Debugbar::addMessage(Input::all(), 'input');
	    // Debugbar::addMessage($result, 'result');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Supplier $supplier)
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
		$supplier = Supplier::find($id);
		$contacts = $supplier->supplier_contacts;
		$action   = 'supplier.update';
		return view('suppliers.edit', compact('supplier', 'contacts'))->with(compact('action'));;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
	    $this->validate($request, [
	        'supplier_name' => 'required|unique:suppliers,supplier_name,' . $id,
	        'web_address'   => 'url'
	        // 'body' => 'required',
	    ]);

	    //create customer
        $supplier = Supplier::find($id);
        $supplier->update(Input::all());

        $contacts = $request->input('contacts');

        foreach( $request->input('contacts') as $key => $contact ) {

        	if ( strpos($key, '::') !== false ) {
        		$supplier->customer_contacts()->create($contact);
        	} else {
				$result = SupplierContact::find($key);
				$result->update($contact);
        	}

        }

        Debugbar::addMessage(Input::all(), 'input');

        // return;        
		// dd($customer->customer_contacts()->save());
		// $contact = $customer->customer_contacts()->save($customer, array('first_name' => 'AAAAAAA'));
		// $contact = new CustomerContact(['first_name' => 'AAAAAAA']);
		// $contact = $customer->customer_contacts()->save($contact);

        return redirect()->route('suppliers.edit', compact('customer'))->with(['message' => 'Supplier updated!', 'action' => $customer])->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$result = Supplier::destroy($id);
		return redirect()->route('suppliers.index')->with(['message' => $result ? 'Supplier deleted!' : 'Something went wrong, please try again.']);
	}

}
