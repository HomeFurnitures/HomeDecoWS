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
Route::post('/login', 'MyAuthController@logIn');
Route::post('/logout', 'MyAuthController@logOut');
//Route::post('/register', 'MyAuthController@register'); // TODO move to user controller

/**
 * Route product requests
 */
Route::resource('product', 'ProductController',
    ['except' => ['create', 'edit']]);


/**
 * Route order requests
 */
Route::get('order/self', 'OrderController@getUserOrders');
Route::resource('order', 'OrderController',
    ['except' => ['create', 'edit']]);

/**
 * Route user requests
 */
Route::get('user/self', 'UserController@getThisUser');
Route::resource('user', 'UserController',
    ['except' => ['create', 'edit']]);