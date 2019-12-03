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
	Route::get('select_product/{type}', ['as'=>'select_product', 'uses'=>'GoodsController@selectProduct']);
	Route::get('single_form', ['as'=>'single_form', 'uses'=>'GoodsController@singleForm']);
});