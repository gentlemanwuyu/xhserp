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

Route::group(['as'=>'index.'], function (){
    Route::get('login', ['as'=>'login_page', 'uses'=>'IndexController@loginPage']);
    Route::post('login', ['as'=>'login', 'uses'=>'IndexController@login']);

    Route::group(['middleware' => ['auth']], function (){
        Route::get('/', ['as'=>'index', 'uses'=>'IndexController@index']);
        Route::get('logout', ['as'=>'logout', 'uses'=>'IndexController@logout']);
        Route::get('home', ['as'=>'home', 'uses'=>'IndexController@home']);
        Route::get('logs', ['as'=>'logs', 'uses'=>'\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);
    });
});

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as'=>'user.'], function (){
    Route::get('index', ['as'=>'index', 'uses'=>'UserController@index']);
    Route::get('paginate', ['as'=>'paginate', 'uses'=>'UserController@paginate']);
    Route::get('form', ['as'=>'form', 'uses'=>'UserController@form']);
    Route::post('save', ['as'=>'save', 'uses'=>'UserController@save']);
    Route::post('delete', ['as'=>'delete', 'uses'=>'UserController@delete']);
});