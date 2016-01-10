<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Supplier;
use App\SupplierContact;

use Illuminate\Support\Facades\Session;
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
			'web_address'   => 'url'
	    ]);

	    // Store input to session
	    Input::flash();

	    // Create Supplier
        $result = Supplier::create(Input::all());

        if ( ! $result->id ) {
			return redirect()->route('suppliers.create')->with('message', 'Unable to create supplier, please try again.');
        }

		foreach( $request->input('contacts') as $key => $contact ) {

			// If at least one of the fields is not null, store contact
			if(!empty($contact['first_name']) || !empty($contact['last_name']) || !empty($contact['phone']) || !empty($contact['mobile']) || !empty($contact['email']))
			{
				$result->supplier_contacts()->create($contact);
			}
		}

        //redirect after successful save to customers.edit
	    return redirect()->route('suppliers.edit', $result->id)->with('message', 'Supplier created!')->withInput();

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
		return view('suppliers.edit', compact('supplier', 'contacts'))->with(compact('action'));
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
	    ]);

	    // Create Supplier
        $supplier = Supplier::find($id);
        $supplier->update(Input::all());

		$supplier_contacts = $supplier->supplier_contacts;
		$contacts_to_delete = array();

		foreach ($supplier_contacts as $contact)
		{
			array_push($contacts_to_delete, $contact->id);
		}

        foreach( $request->input('contacts') as $key => $contact ) {

			// Check for deleting entries
			if(in_array($key, $contacts_to_delete))
			{
				$key_to_delete = array_search($key, $contacts_to_delete);
				unset($contacts_to_delete[$key_to_delete]);
			}

			// If at least one of the fields is not null, store contact
			if(!empty($contact['first_name']) || !empty($contact['last_name']) || !empty($contact['phone']) || !empty($contact['mobile']) || !empty($contact['email']))
			{
				if (strpos($key, '::') !== false) {
					$supplier->supplier_contacts()->create($contact);
				} else {
					$result = SupplierContact::find($key);
					$result->update($contact);
				}
			}
			else
			{
				// Contacts are empty, so check for deleting
				if (strpos($key, '::') === false) {
					$result = SupplierContact::find($key);
					$result->delete();
				}
			}
        }

		// Delete needed contacts
		sort($contacts_to_delete);
		SupplierContact::destroy($contacts_to_delete);

        Debugbar::addMessage(Input::all(), 'input');

        return redirect()->route('suppliers.edit', compact('supplier'))->with(['message' => 'Supplier updated successfully!', 'action' => $supplier])->withInput();
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


	// Show the list of products of a certain supplier
	public function products($id)
	{
		$message = Session::get('message');
		$supplier = Supplier::find($id);
		$products = $supplier->products;

		return view('suppliers.products', compact('supplier', 'products', 'message'));
	}
}
