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

Route::group(['prefix' => 'category', 'as'=>'category.'], function (){
	Route::get('tree/{type}', ['as'=>'tree', 'uses'=>'CategoryController@tree']);
	Route::get('data/{type}', ['as'=>'data', 'uses'=>'CategoryController@data']);
	Route::post('save', ['as'=>'save', 'uses'=>'CategoryController@save']);
	Route::post('delete', ['as'=>'delete', 'uses'=>'CategoryController@delete']);
});