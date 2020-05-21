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

Route::group(['prefix' => 'supplier', 'as'=>'supplier.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'SupplierController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'SupplierController@form']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'SupplierController@paginate']);
	Route::post('save', ['as'=>'save', 'uses'=>'SupplierController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'SupplierController@delete']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'SupplierController@detail']);
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

Route::group(['prefix' => 'returnOrder', 'as'=>'returnOrder.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'ReturnOrderController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'ReturnOrderController@paginate']);
	Route::get('form', ['as'=>'form', 'uses'=>'ReturnOrderController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'ReturnOrderController@save']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'ReturnOrderController@detail']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'ReturnOrderController@delete']);
	Route::post('edit_track_no', ['as'=>'edit_track_no', 'uses'=>'ReturnOrderController@editTrackNo']);
});