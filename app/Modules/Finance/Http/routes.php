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

Route::group(['prefix' => 'collection', 'as'=>'collection.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'CollectionController@index']);
	Route::get('form', ['as'=>'form', 'uses'=>'CollectionController@form']);
});

Route::group(['prefix' => 'pendingCollection', 'as'=>'pendingCollection.'], function (){
	Route::get('index', ['as'=>'index', 'uses'=>'PendingCollectionController@index']);
});