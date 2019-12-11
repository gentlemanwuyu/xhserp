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