<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['prefix' => 'customer', 'as'=>'customer.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'CustomerController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'CustomerController@form']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'CustomerController@paginate']);
	Route::post('save', ['as'=>'save', 'uses'=>'CustomerController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'CustomerController@delete']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'CustomerController@detail']);
});

Route::group(['prefix' => 'order', 'as'=>'order.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'OrderController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'OrderController@form']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'OrderController@paginate']);
	Route::post('save', ['as'=>'save', 'uses'=>'OrderController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'OrderController@delete']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'OrderController@detail']);
	Route::get('review', ['as'=>'review', 'uses'=>'OrderController@review']);
	Route::post('agree', ['as'=>'agree', 'uses'=>'OrderController@agree']);
	Route::post('reject', ['as'=>'reject', 'uses'=>'OrderController@reject']);
	Route::post('cancel', ['as'=>'cancel', 'uses'=>'OrderController@cancel']);
});

Route::group(['prefix' => 'deliveryOrder', 'as'=>'deliveryOrder.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'DeliveryOrderController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'DeliveryOrderController@form']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'DeliveryOrderController@paginate']);
	Route::post('save', ['as'=>'save', 'uses'=>'DeliveryOrderController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'DeliveryOrderController@delete']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'DeliveryOrderController@detail']);
});

Route::group(['prefix' => 'paymentMethod', 'as'=>'paymentMethod.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'PaymentMethodController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'PaymentMethodController@paginate']);
	Route::get('form', ['as'=>'form', 'uses'=>'PaymentMethodController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'PaymentMethodController@save']);
	Route::get('review', ['as'=>'review', 'uses'=>'PaymentMethodController@review']);
	Route::post('agree', ['as'=>'agree', 'uses'=>'PaymentMethodController@agree']);
	Route::post('reject', ['as'=>'reject', 'uses'=>'PaymentMethodController@reject']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'PaymentMethodController@delete']);
});

Route::group(['prefix' => 'returnOrder', 'as'=>'returnOrder.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'ReturnOrderController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'ReturnOrderController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'ReturnOrderController@save']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'ReturnOrderController@paginate']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'ReturnOrderController@detail']);
	Route::post('agree', ['as'=>'agree', 'uses'=>'ReturnOrderController@agree']);
	Route::post('reject', ['as'=>'reject', 'uses'=>'ReturnOrderController@reject']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'ReturnOrderController@delete']);
});