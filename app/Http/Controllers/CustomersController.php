<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\CustomerContact;

use Illuminate\Http\Request;

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
	        // 'body' => 'required',
	    ]);



	    //store input to session
	    Input::flash();

	    //create customer
        $result = Customer::create( Input::all() );

        if ( ! $result->id ) {
			return redirect()->route('customers.create')->with('message', 'Unable to create customer, please try again.');
        }

        //redirect after successful save to customers.edit
	    return redirect()->route('customers.edit', $result->id)->with('message', 'Customer created!')->withInput();

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
	        // 'body' => 'required',
	    ]);

	    // dd( $request->all() );
	    // dd(Input::all());

	    //store input to session
	    // Input::flash();


	    //create customer
        $customer = Customer::find($id);
        $customer->update(Input::all());

        $contacts = $request->input('contacts');

        foreach( $request->input('contacts') as $key => $contact ) {

        	if ( strpos($key, '::') !== false ) {
        		$customer->customer_contacts()->create($contact);
        	} else {
				$result = CustomerContact::find($key);
				$result->update($contact);
        	}

        }

        Debugbar::addMessage(Input::all(), 'input');

        // return;        
		// dd($customer->customer_contacts()->save());
		// $contact = $customer->customer_contacts()->save($customer, array('first_name' => 'AAAAAAA'));
		// $contact = new CustomerContact(['first_name' => 'AAAAAAA']);
		// $contact = $customer->customer_contacts()->save($contact);

        return redirect()->route('customers.edit', compact('customer'))->with(['message' => 'Customer updated!', 'action' => $customer])->withInput();


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

			$array[$i]['quantity'] = 0;
			foreach($quote->qris as $item) {
				$array[$i]['quantity'] += $item->quantity;
			}

			$array[$i]['request_date'] = $quote->request_date;

			$i++;
		}

		return view('customers.history', compact('customer', 'array'));
	}

}

