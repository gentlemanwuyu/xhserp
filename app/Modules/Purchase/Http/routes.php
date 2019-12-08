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
});

Route::group(['prefix' => 'order', 'as'=>'order.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'OrderController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'OrderController@form']);
});