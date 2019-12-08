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
});