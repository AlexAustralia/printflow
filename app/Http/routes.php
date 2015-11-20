<?php

Route::get('/', 'QuoteRequestsController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
]);

// JSON URLs for auto complete boxes
Route::get('json/suppliers', 'JsonController@suppliers');
Route::get('json/customers', 'JsonController@customers');

// Customer section
Route::get('customers/{id}/history', 'CustomersController@history');
Route::resource('customers', 'CustomersController');

// Route::resource('customer_contacts', 'CustomerContactsController');
// Route::resource('customer_addresses', 'CustomerAddressesController');
Route::resource('suppliers', 'SuppliersController');



Route::resource('quote_requests', 'QuoteRequestsController');

Route::resource('quotes', 'QuotesController');

// View list of suppliers for quote request id
Route::get('/choose_suppliers/{id}', 'QuotesController@get_choose_suppliers');
// Add or remove supplier from list
Route::post('/choose_suppliers/{id}', 'QuotesController@post_choose_suppliers');

//
Route::get('/send_rfq_emails/{id}', 'QuotesController@get_send_rfq_emails');
Route::post('/send_rfq_emails/{id}', 'QuotesController@post_send_rfq_emails');

Route::get('/enter_prices/{qrid}', 'QuotesController@get_enter_prices');
Route::get('/enter_prices/{qrid}/{qid}', 'QuotesController@get_enter_prices');
Route::post('/enter_prices/{qrid}', 'QuotesController@post_enter_prices');
Route::post('/enter_prices/{qrid}/{qid}', 'QuotesController@post_enter_prices');

Route::get('/evaluate/{qrid}', 'QuotesController@get_evaluate');
Route::post('/evaluate/{qrid}', 'QuotesController@post_evaluate');

Route::get('/send_customer_quote/{qrid}', 'QuotesController@get_send_customer_quote');
Route::post('/send_customer_quote/{qrid}', 'QuotesController@post_send_customer_quote');