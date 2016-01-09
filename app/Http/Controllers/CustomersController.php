<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\CustomerContact;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use URL;
use Input;
use Debugbar;

class CustomersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$customers = Customer::all()->sortBy('customer_name');

		return view('customers.index', compact('customers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$customer = new Customer;
		$contacts = $customer->customer_contacts;
		return view('customers.create', compact('customer', 'contacts'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

	    $this->validate($request, [
	        'customer_name' => 'required|unique:customers',
			'web_address'   => 'url'
	    ]);

	    // Store input to session
	    Input::flash();

	    //Create Customer
        $result = Customer::create(Input::all());

        if ( ! $result->id ) {
			return redirect()->route('customers.create')->with('message', 'Unable to create customer, please try again.');
        }

		foreach( $request->input('contacts') as $key => $contact ) {

			// If at least one of the fields is not null, store contact
			if(!empty($contact['first_name']) || !empty($contact['last_name']) || !empty($contact['phone']) || !empty($contact['mobile']) || !empty($contact['email']))
			{
				$result->customer_contacts()->create($contact);
			}
		}

        //redirect after successful save to customers.edit
	    return redirect()->route('customers.edit', $result->id)->with('message', 'Customer created!')->withInput();

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Customer $customer)
	{
		return redirect('/customers');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$customer = Customer::find($id);
		$contacts = $customer->customer_contacts;
		$action   = 'customer.update';
		return view('customers.edit', compact('customer', 'contacts'))->with(compact('action'));;
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
	        'customer_name' => 'required|unique:customers,customer_name,' . $id,
	        'web_address'   => 'url'
	    ]);

	    // Create customer
        $customer = Customer::find($id);
        $customer->update(Input::all());

		$customer_contacts = $customer->customer_contacts;
		$contacts_to_delete = array();

		foreach ($customer_contacts as $contact)
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
					$customer->customer_contacts()->create($contact);
				} else {
					$result = CustomerContact::find($key);
					$result->update($contact);
				}
			}
			else
			{
				// Contacts are empty, so check for deleting
				if (strpos($key, '::') === false) {
					$result = CustomerContact::find($key);
					$result->delete();
				}
			}
		}

		// Delete needed contacts
		sort($contacts_to_delete);
		CustomerContact::destroy($contacts_to_delete);

        Debugbar::addMessage(Input::all(), 'input');

        return redirect()->route('customers.edit', compact('customer'))->with(['message' => 'Customer updated successfully!', 'action' => $customer])->withInput();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$result = Customer::destroy($id);
		return redirect()->route('customers.index')->with(['message' => $result ? 'Customer deleted!' : 'Something went wrong, please try again.']);
	}

	/**
	 * Show the history page for particular customers.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function history($id)
	{
		$message = Session::get('message');
		$customer = Customer::find($id);
		$quotes = $customer->quotes;

		$array = array();
		$i = 0;

		foreach($quotes as $quote)
		{
			$array[$i]['quote_number'] = $quote->id;
			if(isset($quote->job->id)) {
				$array[$i]['job_number'] = $quote->job->id;
				$array[$i]['supplier_id'] = $quote->job->supplier->id;
				$array[$i]['supplier_name'] = $quote->job->supplier->supplier_name;
				$array[$i]['job_cost'] = $quote->job->net_cost;
				$array[$i]['job_sell'] = $quote->job->net_sell;
			}
			else {
				$array[$i]['job_number'] = '';
				$array[$i]['supplier_id'] = '';
				$array[$i]['supplier_name'] = '';
				$array[$i]['job_cost'] = '';
				$array[$i]['job_sell'] = '';
			}
			$array[$i]['title'] = $quote->title;
			$array[$i]['description'] = $quote->summary;
			$array[$i]['artwork_image'] = $quote->artwork_image;

			$array[$i]['quantity'] = 0;
			foreach($quote->qris as $item) {
				$array[$i]['quantity'] += $item->quantity;
			}

			$array[$i]['request_date'] = $quote->request_date;
			$array[$i]['expiry_date'] = $quote->expiry_date;

			//TODO: add status value after Workflow module done
			$array[$i]['status'] = 'invoiced';

			$i++;
		}

		return view('customers.history', compact('customer', 'array', 'message'));
	}

}

