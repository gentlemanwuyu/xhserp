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

Route::group(['prefix' => 'collection', 'as'=>'collection.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'CollectionController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'CollectionController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'CollectionController@save']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'CollectionController@paginate']);
});

Route::group(['prefix' => 'pendingCollection', 'as'=>'pendingCollection.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'PendingCollectionController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'PendingCollectionController@paginate']);
	Route::get('deduction', ['as'=>'deduction', 'uses'=>'PendingCollectionController@deduction']);
	Route::post('deduct', ['as'=>'deduct', 'uses'=>'PendingCollectionController@deduct']);
});

Route::group(['prefix' => 'payment', 'as'=>'payment.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'PaymentController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'PaymentController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'PaymentController@save']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'PaymentController@paginate']);
});

Route::group(['prefix' => 'pendingPayment', 'as'=>'pendingPayment.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'PendingPaymentController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'PendingPaymentController@paginate']);
	Route::get('deduction', ['as'=>'deduction', 'uses'=>'PendingPaymentController@deduction']);
	Route::post('deduct', ['as'=>'deduct', 'uses'=>'PendingPaymentController@deduct']);
});

Route::group(['prefix' => 'account', 'as'=>'account.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'AccountController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'AccountController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'AccountController@save']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'AccountController@paginate']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'AccountController@delete']);
});