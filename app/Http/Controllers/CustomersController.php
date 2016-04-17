<?php namespace App\Http\Controllers;

use App\CustomerAddress;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\CustomerContact;

use App\QuoteItem;
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

		$this->push_to_xero($result->id);

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
		$delivery_addresses = $customer->delivery_addresses;

		$message = Session::get('message');
		$action   = 'customer.update';
		return view('customers.edit', compact('customer', 'contacts', 'delivery_addresses', 'message'))->with(compact('action'));;
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

		$this->push_to_xero($id);

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
			$array[$i]['title'] = $quote->title;
			$array[$i]['description'] = $quote->summary;
			$array[$i]['artwork_image'] = $quote->artwork_image;

			$array[$i]['request_date'] = $quote->request_date;
			$array[$i]['expiry_date'] = $quote->expiry_date;

			// Quotes
			if(isset($quote->get_quote->id)) {
				$array[$i]['supplier_id'] = $quote->get_quote->supplier->id;
				$array[$i]['supplier_name'] = $quote->get_quote->supplier->supplier_name;
			}
			else {
				$array[$i]['supplier_id'] = '';
				$array[$i]['supplier_name'] = '';
			}

			//Jobs
			if(isset($quote->job->id) && ($quote->quote_id != 0)) {
				$array[$i]['job_number'] = $quote->id;
				$array[$i]['job_sell'] = $quote->job->job_item->total;
				$array[$i]['quantity'] = $quote->job->job_item->quantity;
				$array[$i]['request_date'] = $quote->job->updated_at->format('d/m/Y');

				$query = QuoteItem::where('qri_id', $quote->job->quote_request_items_id)
					->where('quote_id', $quote->get_quote->id)->first();

				$array[$i]['job_cost'] = $query->total_buy_cost;
			}
			else {
				$array[$i]['job_number'] = '';
				$array[$i]['job_cost'] = '';
				$array[$i]['job_sell'] = '';
				$array[$i]['quantity'] = '';
			}

			//TODO: add status value after Workflow module done
			$array[$i]['status'] = 'invoiced';

			$i++;
		}

		return view('customers.history', compact('customer', 'array', 'message'));
	}

	private function push_to_xero($id)
	{
		// Get data for sending customer details
		$customer = Customer::find($id);
		$contacts = $customer->customer_contacts;

		$contactsXML = '<ContactPersons>';

		foreach($contacts as $contact) {
			$contactsXML .= '<ContactPerson>
							 <FirstName>'.$contact->first_name.'</FirstName>
							 <LastName>'.$contact->last_name.'</LastName>
							 <EmailAddress>'.$contact->email.'</EmailAddress>
							 </ContactPerson>';
		}
		$contactsXML .= '</ContactPersons>';

		if(count($contacts) > 0) {
			$first_name = $contacts[0]->first_name;
			$last_name = $contacts[0]->last_name;
			$email = $contacts[0]->email;
		}
		else
		{
			$first_name = '';
			$last_name = '';
			$email = '';
		}

		define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
		define ( "XRO_APP_TYPE", "Private" );
		define ( "OAUTH_CALLBACK", 'http://printflow.local:8000/' );

		/* For Demo-Company
        define ( "OAUTH_CALLBACK", 'http://printflow.local:8000/' );
        $useragent = "Demo-Printflow";

        $signatures = array (
            'consumer_key'     => 'NLOXKOEM8QUFCW9XCKWH7DQMARCWUW',
            'shared_secret'    => 'YEACQD0QQ2R5X1YCBFV6LZKMMLIYRT',
            // API versions
            'core_version' => '2.0',
            'payroll_version' => '1.0'
        );
        */

		$useragent = env('USER_AGENT');

		$signatures = array (
			'consumer_key'     => env('XERO_KEY'),
			'shared_secret'    => env('XERO_SECRET'),
			// API versions
			'core_version' => '2.0',
			'payroll_version' => '1.0'
		);

		if (XRO_APP_TYPE == "Private" || XRO_APP_TYPE == "Partner") {
			$signatures ['rsa_private_key'] = BASE_PATH . '/certs/privatekey.pem';
			$signatures ['rsa_public_key'] = BASE_PATH . '/certs/publickey.cer';
		}

		$XeroOAuth = new \XeroOAuth(array_merge(array('application_type' => XRO_APP_TYPE, 'oauth_callback' => OAUTH_CALLBACK,
			'user_agent' => $useragent), $signatures));

		$initialCheck = $XeroOAuth->diagnostics();
		$checkErrors = count($initialCheck);
		if ($checkErrors > 0)
		{
			// you could handle any config errors here, or keep on truckin if you like to live dangerously
			foreach($initialCheck as $check) {
				echo 'Error: ' . $check . PHP_EOL;
			}
		}
		else
		{
			$session = $this->persistSession(array(
				'oauth_token' => $XeroOAuth->config['consumer_key'],
				'oauth_token_secret' => $XeroOAuth->config['shared_secret'],
				'oauth_session_handle' => ''
			));
			$oauthSession = $this->retrieveSession();

			if (isset ($oauthSession['oauth_token'])) {
				$XeroOAuth->config['access_token'] = $oauthSession['oauth_token'];
				$XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];

				if (isset($_REQUEST)) {
					if(!isset($_REQUEST['where'])) $_REQUEST['where'] = "";
				}

				if (isset($_REQUEST['wipe'])) {
					session_destroy();
					header("Location: {$here}");

					// already got some credentials stored?
				} elseif (isset($_REQUEST['refresh'])) {
					$response = $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['oauth_session_handle']);
					if ($XeroOAuth->response['code'] == 200) {
						$session = $this->persistSession($response);
						$oauthSession = $this->retrieveSession();
					} else {
						$this->outputError($XeroOAuth);
						if ($XeroOAuth->response['helper'] == "TokenExpired") $XeroOAuth->refreshToken($oauthSession['oauth_token'], $oauthSession['session_handle']);
					}

					} elseif (isset($oauthSession['oauth_token']) && isset($_REQUEST)) {

					$XeroOAuth->config['access_token'] = $oauthSession['oauth_token'];
					$XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];
					$XeroOAuth->config['session_handle'] = $oauthSession['oauth_session_handle'];

					$xml = '<Contacts>
							 <Contact>
							   <Name>'.$customer->customer_name.'</Name>
							   <FirstName>'.$first_name.'</FirstName>
							   <LastName>'.$last_name.'</LastName>
							   <EmailAddress>'.$email.'</EmailAddress>
							   <Addresses>
									<Address>
										<AddressType>POBOX</AddressType>
										<AttentionTo>'.$customer->postal_attention.'</AttentionTo>
										<AddressLine1>'.$customer->customer_name.'</AddressLine1>
										<AddressLine2>'.$customer->postal_street.'</AddressLine2>
										<AddressLine3> </AddressLine3>
										<AddressLine4> </AddressLine4>
										<City>'.$customer->postal_city.'</City>
										<Region>'.$customer->postal_state.'</Region>
										<PostalCode>'.$customer->postal_postcode.'</PostalCode>
										<Country>'.$customer->postal_country.'</Country>
									</Address>
									<Address>
										<AddressType>STREET</AddressType>
										<AttentionTo>'.$customer->postal_attention.'</AttentionTo>
										<AddressLine1>'.$customer->customer_name.'</AddressLine1>
										<AddressLine2>'.$customer->postal_street.'</AddressLine2>
										<AddressLine3> </AddressLine3>
										<AddressLine4> </AddressLine4>
										<City>'.$customer->postal_city.'</City>
										<Region>'.$customer->postal_state.'</Region>
										<PostalCode>'.$customer->postal_postcode.'</PostalCode>
										<Country>'.$customer->postal_country.'</Country>
									</Address>
							   </Addresses>
							   <Phones>
									<Phone>
										<PhoneType>DEFAULT</PhoneType>
										<PhoneNumber>'.$customer->tel_number.'</PhoneNumber>
										<PhoneAreaCode>'.$customer->tel_area.'</PhoneAreaCode>
										<PhoneCountryCode>'.$customer->tel_country.'</PhoneCountryCode>
									</Phone>
									<Phone>
										<PhoneType>MOBILE</PhoneType>
										<PhoneNumber>'.$customer->mobile_number.'</PhoneNumber>
										<PhoneAreaCode>'.$customer->mobile_area.'</PhoneAreaCode>
										<PhoneCountryCode>'.$customer->mobile_country.'</PhoneCountryCode>
									</Phone>
							   </Phones>
							   <Website>'.$customer->web_address.'</Website>
							 <IsCustomer>true</IsCustomer>
							   '.$contactsXML.'
							 </Contact>
						   </Contacts>';

					$response = $XeroOAuth->request('POST', $XeroOAuth->url('Contacts', 'core'), array(), $xml);
					if ($XeroOAuth->response['code'] == 200)
					{
						return 'OK';

					} else {
						$this->outputError($XeroOAuth);
						return 'ERROR';
					}
				}
			}
		}

		return 'ERROR';
	}

	/**
	 * Persist the OAuth access token and session handle
	 */
	function persistSession($response)
	{
		if (isset($response)) {
			$_SESSION['access_token']       = $response['oauth_token'];
			$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
			if(isset($response['oauth_session_handle']))  $_SESSION['session_handle']     = $response['oauth_session_handle'];
		} else {
			return false;
		}

	}


	/**
	 * Retrieve the OAuth access token and session handle
	 */
	private function retrieveSession()
	{
		if (isset($_SESSION['access_token'])) {
			$response['oauth_token']            =    $_SESSION['access_token'];
			$response['oauth_token_secret']     =    $_SESSION['oauth_token_secret'];
			$response['oauth_session_handle']   =    $_SESSION['session_handle'];
			return $response;
		} else {
			return false;
		}

	}

	private function outputError($XeroOAuth)
	{
		//echo $customerName;
		echo 'Error: ' . $XeroOAuth->response['response'] . PHP_EOL;
		$this->pr($XeroOAuth);
	}

	/**
	 * Debug function for printing the content of an object
	 *
	 * @param mixes $obj
	 */
	private function pr($obj)
	{
		if (!$this->is_cli())
			echo '<pre style="word-wrap: break-word">';
		if (is_object($obj))
			print_r($obj);
		elseif (is_array($obj))
			print_r($obj);
		else
			echo $obj;
		if (!$this->is_cli())
			echo '</pre>';
	}

	private function is_cli()
	{
		return (PHP_SAPI == 'cli' && empty($_SERVER['REMOTE_ADDR']));
	}

}

