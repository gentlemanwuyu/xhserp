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
});

Route::group(['prefix' => 'single', 'as'=>'single.'], function (){
	Route::get('select_product', ['as'=>'select_product', 'uses'=>'SingleController@selectProduct']);
	Route::get('form', ['as'=>'form', 'uses'=>'SingleController@form']);
});

Route::group(['prefix' => 'combo', 'as'=>'combo.'], function (){
	Route::get('select_product', ['as'=>'select_product', 'uses'=>'ComboController@selectProduct']);
});