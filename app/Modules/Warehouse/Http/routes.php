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

Route::group(['prefix' => 'inventory', 'as'=>'inventory.'], function (){
	Route::get('form', ['as'=>'form', 'uses'=>'InventoryController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'InventoryController@save']);
});

Route::group(['prefix' => 'stockout', 'as'=>'stockout.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'StockoutController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'StockoutController@paginate']);
});

Route::group(['prefix' => 'entry', 'as'=>'entry.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'EntryController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'EntryController@paginate']);
	Route::get('form', ['as'=>'form', 'uses'=>'EntryController@form']);
	Route::post('save', ['as'=>'save', 'uses'=>'EntryController@save']);
});

Route::group(['prefix' => 'express', 'as'=>'express.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'ExpressController@index']);
	Route::get('paginate', ['as'=>'paginate', 'uses'=>'ExpressController@paginate']);
	Route::post('save', ['as'=>'save', 'uses'=>'ExpressController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'ExpressController@delete']);
});