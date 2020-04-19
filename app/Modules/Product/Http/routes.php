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

Route::group(['prefix' => 'product', 'as'=>'product.'], function (){
	Route::get('list', ['as'=>'list', 'uses'=>'ProductController@getList']);
	Route::get('form', ['as'=>'form', 'uses'=>'ProductController@form']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'ProductController@paginate']);
	Route::post('save', ['as'=>'save', 'uses'=>'ProductController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'ProductController@delete']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'ProductController@detail']);
	Route::get('order_paginate', ['as'=>'order_paginate', 'uses'=>'ProductController@orderPaginate']);
});