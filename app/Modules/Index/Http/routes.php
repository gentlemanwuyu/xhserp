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
        Route::get('home_data', ['as'=>'home_data', 'uses'=>'IndexController@homeData']);
        Route::get('logs', ['as'=>'logs', 'uses'=>'\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);
    });
});

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as'=>'user.'], function (){
    Route::get('index', ['as'=>'index', 'uses'=>'UserController@index']);
    Route::get('paginate', ['as'=>'paginate', 'uses'=>'UserController@paginate']);
    Route::get('form', ['as'=>'form', 'uses'=>'UserController@form']);
    Route::post('save', ['as'=>'save', 'uses'=>'UserController@save']);
    Route::post('delete', ['as'=>'delete', 'uses'=>'UserController@delete']);
    Route::get('password_form', ['as'=>'password_form', 'uses'=>'UserController@passwordForm']);
    Route::post('reset_password', ['as'=>'reset_password', 'uses'=>'UserController@resetPassword']);
    Route::get('assign_permission', ['as'=>'assign_permission', 'uses'=>'UserController@assignPermission']);
    Route::post('assign', ['as'=>'assign', 'uses'=>'UserController@assign']);
    Route::post('disable', ['as'=>'disable', 'uses'=>'UserController@disable']);
    Route::get('detail', ['as'=>'detail', 'uses'=>'UserController@detail']);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'config', 'as'=>'config.'], function (){
    Route::get('index', ['as'=>'index', 'uses'=>'ConfigController@index']);
    Route::get('paginate', ['as'=>'paginate', 'uses'=>'ConfigController@paginate']);
    Route::get('form', ['as'=>'form', 'uses'=>'ConfigController@form']);
    Route::post('save', ['as'=>'save', 'uses'=>'ConfigController@save']);
    Route::post('delete', ['as'=>'delete', 'uses'=>'ConfigController@delete']);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'permission', 'as'=>'permission.'], function (){
    Route::get('index', ['as'=>'index', 'uses'=>'PermissionController@index']);
    Route::get('paginate', ['as'=>'paginate', 'uses'=>'PermissionController@paginate']);
    Route::get('form', ['as'=>'form', 'uses'=>'PermissionController@form']);
    Route::post('save', ['as'=>'save', 'uses'=>'PermissionController@save']);
    Route::post('delete', ['as'=>'delete', 'uses'=>'PermissionController@delete']);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'role', 'as'=>'role.'], function (){
    Route::get('index', ['as'=>'index', 'uses'=>'RoleController@index']);
    Route::get('paginate', ['as'=>'paginate', 'uses'=>'RoleController@paginate']);
    Route::get('form', ['as'=>'form', 'uses'=>'RoleController@form']);
    Route::post('save', ['as'=>'save', 'uses'=>'RoleController@save']);
    Route::post('delete', ['as'=>'delete', 'uses'=>'RoleController@delete']);
    Route::get('detail', ['as'=>'detail', 'uses'=>'RoleController@detail']);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'organization', 'as'=>'organization.'], function (){
    Route::get('index', ['as'=>'index', 'uses'=>'OrganizationController@index']);
});