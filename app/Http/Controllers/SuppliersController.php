<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SupplierReview;
use App\User;
use Carbon\Carbon;
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
			'web_address'   => 'url',
			'common_email'  => 'required'
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
	        'web_address'   => 'url',
			'common_email'  => 'required'
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

	/**
	 * Show Supplier Review page
	 *
	 * @param $id
	 * @return \Illuminate\View\View
	 */
	public function review($id)
	{
		$supplier = Supplier::find($id);
		$review = $supplier->review;

		$valuation = ['Excellent', 'Good', 'Satisfactory', 'Fair', 'Poor', 'Not Rated', 'Not Applicable'];
		$yesno = ['Yes', 'No', 'Not Applicable', 'No Assessed'];
		$ages = ['1-3 Years Old', '4-5 Years Old', '6-10 Years Old', '11 Years and Older', 'Not Applicable'];

		return view('suppliers.review', compact('supplier', 'review', 'valuation', 'yesno', 'ages'));
	}

	/**
	 * Update Supplier Review page
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function review_update($id)
	{
		$input = Input::all();

		$input['supplier_id'] = $id;
		unset($input['_token']);
		$input['date_visited'] = Carbon::createFromFormat('d/m/Y', $input['date_visited']);

		$supplier_review = isset($input['id']) ? SupplierReview::find($input['id']) : new SupplierReview();

		$supplier_review->uv = null;
		$supplier_review->coater = null;

		foreach ($input as $key => $value) {
			if (substr($key, 0, 5) != 'photo') {
				if (is_array($value)) $input[$key] = json_encode($value);

				$supplier_review->$key = $input[$key];
			}
		}

		$supplier_review->save();

		// Proceed with photos
		$this->process_photo(isset($input['photo_office_erase']) ? $input['photo_office_erase'] : null, 'photo_office', $supplier_review);
		$this->process_photo(isset($input['photo_warehouse_erase']) ? $input['photo_warehouse_erase'] : null, 'photo_warehouse', $supplier_review);
		$this->process_photo(isset($input['photo_pre_press_erase']) ? $input['photo_pre_press_erase'] : null, 'photo_pre_press', $supplier_review);
		$this->process_photo(isset($input['photo_finishing_erase']) ? $input['photo_finishing_erase'] : null, 'photo_finishing', $supplier_review);

		return redirect('/suppliers/'.$id.'/review');
	}

	/**
	 * Show Supplier Access to Review page
	 *
	 * @param $id
	 * @return \Illuminate\View\View
	 */
	public function access($id)
	{
		$supplier = Supplier::find($id);
		$allowed_users_id = is_null($supplier->access_to_review) ? [] : unserialize($supplier->access_to_review);

		$users = User::select('id', 'name')->where('admin', 0)->get();

		$allowed_users = $users->filter(function($user) use ($allowed_users_id) {
			return in_array($user->id, $allowed_users_id);
		});

		$users = $users->reject(function($user) use ($allowed_users_id) {
			return in_array($user->id, $allowed_users_id);
		});

		$message = Session::get('message');

		return view('suppliers.access', compact('supplier', 'users', 'allowed_users', 'message'));
	}

	/**
	 * Save Supplier Access to Review page
	 *
	 * @param $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function access_update($id)
	{
		$input = Input::all();

		$supplier = Supplier::find($id);

		$supplier->access_to_review = isset($input['allowed_users']) ? serialize($input['allowed_users']) : null;

		$supplier->save();

		return redirect('suppliers/'.$id.'/access')->with('message', 'Access to Review page has been updated successfully');
	}


	private function process_photo($to_erase, $photo, $supplier_review)
	{
		// Erase ticked photo
		if(!is_null($to_erase)) {
			$file_names_array = is_null($supplier_review->$photo) ? [] : unserialize($supplier_review->$photo);

			foreach ($to_erase as $erase_file_index) {
				$path = 'uploads/supplier-reviews/' . $file_names_array[$erase_file_index];

				if (file_exists($path)) {
					unlink($path);
				}

				unset($file_names_array[$erase_file_index]);
			}

			$supplier_review->$photo = serialize($file_names_array);
			$supplier_review->save();
		}

		// Storing photo files
		if (Input::hasFile($photo))
		{
			$files = Input::file($photo);
			$file_names_array = is_null($supplier_review->$photo) ? [] : unserialize($supplier_review->$photo);

			foreach ($files as $file)
			{
				if ($file->isValid()) {
					$filename = rand(111111,999999).'-'.$file->getClientOriginalName();
					$destination_path = 'uploads/supplier-reviews/';

					$file->move($destination_path, $filename);

					if (!in_array($filename, $file_names_array))
						array_push($file_names_array, $filename);
				}
			}

			$supplier_review->$photo = serialize($file_names_array);
			$supplier_review->save();
		}
	}
}
