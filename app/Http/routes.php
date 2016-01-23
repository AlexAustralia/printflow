<?php

Route::group(['middleware' => 'auth'], function(){

	//Main page
	Route::get('/', 'QuoteRequestsController@index');

	// Customer section
	Route::get('customers/{id}/history', 'CustomersController@history');
	Route::resource('customers', 'CustomersController');

	// Customer Delivery Addresses
	Route::get('customer/{id}/create_address/{job}', 'CustomerAddressesController@create');
	Route::post('customer_address/save', 'CustomerAddressesController@store');

    // Route::resource('customer_contacts', 'CustomerContactsController');

	// Supplier section
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

	// Products Library
	Route::get('products', 'ProductsController@index');
	Route::get('products/create', 'ProductsController@create');
	Route::get('products/create/{id}', 'ProductsController@create');
	Route::post('products/save', 'ProductsController@save');
	Route::get('products/{id}/edit', 'ProductsController@edit');
	Route::get('products/{id}/edit/{page}', 'ProductsController@edit');
	Route::post('products/delete', 'ProductsController@delete');





	// Not tested routes



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














