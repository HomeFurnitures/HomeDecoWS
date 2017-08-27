<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Route login requests
 */
Route::post('/login', 'LoginController@logIn');
Route::post('/logout', 'LoginController@logOut');
Route::get('/check_login', 'LoginController@checkLogIn');
Route::post('/image', 'ImageController@saveImage');

/**
 * Route product requests
 */
Route::resource('product', 'ProductController',
    ['except' => ['create', 'edit']]);

/**
 * Route custom product requests
 */
Route::resource('custom_product', 'CustomProductController',
    ['except' => ['create', 'edit']]);

/**
 * Route product requests
 */
Route::resource('message', 'MessageController',
    ['except' => ['create', 'edit', 'show', 'update']]);


/**
 * Route order requests
 */
Route::get('order/self', 'OrderController@getUserOrders');
Route::resource('order', 'OrderController',
    ['except' => ['create', 'edit']]);

/**
 * Route custom order requests
 */
Route::get('custom_order/self', 'CustomOrderController@getUserOrders');
Route::resource('custom_order', 'CustomOrderController',
    ['except' => ['create', 'edit']]);

/**
 * Route user requests
 */
Route::get('user/self', 'UserController@getThisUser');
Route::put('user/self', 'UserController@updateThisUser');
Route::resource('user', 'UserController',
    ['except' => ['create', 'edit']]);

