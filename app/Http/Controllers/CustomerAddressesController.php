<?php namespace App\Http\Controllers;

use App\Customer;
use App\CustomerAddress;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CustomerAddressesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id, $job = 0)
	{
		$customer = Customer::find($id);

		return view('customer_addresses.create', compact('customer', 'job'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		// Validate form
		$this->validate($request, [
			'name' => 'required',
			'address' => 'required',
			'city' => 'required',
			'state' => 'required',
			'postcode' => 'required'
		]);

		$input = Input::all();

		if (isset($input['id'])) {
			$customer_address = CustomerAddress::find($input['id']);
			$customer_address->update($input);
		} else {
			$customer_address = CustomerAddress::create($input);
		}

		if($input['job'] != 0)
		{
			return redirect('/job/' . $input['job'] . '/delivery')->with('message', 'Delivery Address has been stored successfully');
		}
		else
		{
			return redirect('/customers/' . $input['customer_id'] . '/edit')->with('message', 'Delivery Address has been stored successfully');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(CustomerAddress $add)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, $job)
	{
		$customer_address = CustomerAddress::find($id);
		$customer = $customer_address->customer_name;

		return view('customer_addresses.edit', compact('customer', 'job', 'customer_address'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CustomerAddress $add)
	{
		//
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
