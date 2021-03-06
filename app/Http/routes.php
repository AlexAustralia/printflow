<?php

Route::group(['middleware' => 'auth'], function(){

	//Main page
	Route::get('/', 'WorkflowController@index');
	Route::get('/change_status/{id}/{status}', 'WorkflowController@change_status');
	Route::get('send_invoice/{id}', 'WorkflowController@send_invoice');


	// Customer section
	Route::get('customers/{id}/history', 'CustomersController@history');
	Route::resource('customers', 'CustomersController');

	// Customer Delivery Addresses
	Route::get('customer/{id}/create_address/{job}', 'CustomerAddressesController@create');
	Route::get('customer/{id}/create_address', 'CustomerAddressesController@create');
	Route::post('customer_address/save', 'CustomerAddressesController@store');
	Route::get('customer/edit_address/{id}/{job}', 'CustomerAddressesController@edit');

    // Route::resource('customer_contacts', 'CustomerContactsController');

	// Supplier section
	Route::get('suppliers/{id}/review', 'SuppliersController@review');
	Route::post('suppliers/{id}/review/update', 'SuppliersController@review_update');
	Route::get('suppliers/{id}/products', 'SuppliersController@products');
	Route::resource('suppliers', 'SuppliersController');

	//Quotes Section
	Route::get('quote_requests/create/{id}', 'QuoteRequestsController@create');
	Route::post('quote_requests/delete', 'QuoteRequestsController@delete');
	Route::resource('quote_requests', 'QuoteRequestsController');

	// View list of suppliers for quote request id
	Route::get('/choose_suppliers/{id}', 'QuotesController@get_choose_suppliers');

	// Choose Suppliers section on the Quotes Module - Add or remove supplier from list
	Route::post('/choose_suppliers/{id}', 'QuotesController@post_choose_suppliers');

	// Request Supplier Quotes on the Quote Module
	Route::get('/send_rfq_emails/{id}', 'QuotesController@get_send_rfq_emails');
	Route::post('/send_rfq_emails/{id}', 'QuotesController@post_send_rfq_emails');

	// Artwork page
	Route::get('artwork/{id}', 'QuotesController@get_artwork');
	Route::post('artwork/{id}', 'QuotesController@post_artwork');

	// Freight page
	Route::get('freight/{id}', 'QuotesController@get_freight');
	Route::post('freight/{id}', 'QuotesController@post_freight');

	// Enter Supplier Prices on the Quote Module
	Route::get('/enter_prices/{qrid}', 'QuotesController@get_enter_prices');
	Route::get('/enter_prices/{qrid}/{qid}', 'QuotesController@get_enter_prices');
	Route::post('/enter_prices/{qrid}', 'QuotesController@post_enter_prices');
	Route::post('/enter_prices/{qrid}/{qid}', 'QuotesController@post_enter_prices');

	// Evaluate Prices
	Route::get('/evaluate/{qrid}', 'QuotesController@get_evaluate');
	Route::post('/evaluate/{qrid}', 'QuotesController@post_evaluate');

	// Quotes - others
	Route::resource('quotes', 'QuotesController');

	// Jobs
	Route::get('job/{id}/edit', 'JobsController@edit');
	Route::post('job/{id}/save', 'JobsController@save');
	Route::get('job/{id}/delivery', 'JobsController@delivery_get');
	Route::post('job/delivery', 'JobsController@delivery_post');
	Route::get('job/delivery/docket/{id}', 'JobsController@show_docket');
	Route::get('job/delivery/sticker/{id}', 'JobsController@show_sticker');

	// Products Library
	Route::get('products', 'ProductsController@index');
	Route::get('products/create', 'ProductsController@create');
	Route::get('products/create/{id}', 'ProductsController@create');
	Route::post('products/save', 'ProductsController@save');
	Route::get('products/{id}/edit', 'ProductsController@edit');
	Route::get('products/{id}/edit/{page}', 'ProductsController@edit');
	Route::post('products/delete', 'ProductsController@delete');

	// Projects
	Route::get('projects/brief', 'ProjectsController@brief');
	Route::get('projects/discussion', 'ProjectsController@discussion');
	Route::post('projects/discussion/save', 'ProjectsController@saveDiscussion');
	Route::post('projects/discussion/delete', 'ProjectsController@deleteMessage');
	Route::get('projects/checklist', 'ProjectsController@checklist');

	Route::get('/send_customer_quote/{qrid}', 'QuotesController@get_send_customer_quote');
	Route::post('/send_customer_quote/{qrid}', 'QuotesController@post_send_customer_quote');

	// JSON URLs for auto complete boxes
	Route::get('json/suppliers', 'JsonController@suppliers');
	Route::get('json/customers', 'JsonController@customers');
});

Route::pattern('id', '[0-9]+');
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => 'admin'], function() {

	// Users
	Route::get('users', 'UserController@index');
	Route::get('users/create', 'UserController@create');
	Route::get('users/{id}/edit', 'UserController@edit');
	Route::post('users/save', 'UserController@save');
	Route::post('users/delete', 'UserController@delete');

	// Terms
	Route::get('terms', 'TermsController@get_terms');
	Route::post('terms', 'TermsController@post_terms');

	// Access to Review Supplier page
	Route::get('suppliers/{id}/access', 'SuppliersController@access');
	Route::post('suppliers/{id}/access/update', 'SuppliersController@access_update');
});