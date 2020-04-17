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

Route::group(['prefix' => 'goods', 'as'=>'goods.'], function (){
	Route::get('list', ['as'=>'list', 'uses'=>'GoodsController@getList']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'GoodsController@paginate']);
	Route::get('detail', ['as'=>'detail', 'uses'=>'GoodsController@detail']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'GoodsController@delete']);
});

Route::group(['prefix' => 'single', 'as'=>'single.'], function (){
	Route::get('select_product', ['as'=>'select_product', 'uses'=>'SingleController@selectProduct']);
	Route::get('product_paginate', ['as'=>'product_paginate', 'uses'=>'SingleController@productPaginate']);
	Route::get('form', ['as'=>'form', 'uses'=>'SingleController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'SingleController@save']);
});

Route::group(['prefix' => 'combo', 'as'=>'combo.'], function (){
	Route::get('select_product', ['as'=>'select_product', 'uses'=>'ComboController@selectProduct']);
	Route::get('product_paginate', ['as'=>'product_paginate', 'uses'=>'ComboController@productPaginate']);
	Route::get('form', ['as'=>'form', 'uses'=>'ComboController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'ComboController@save']);
});