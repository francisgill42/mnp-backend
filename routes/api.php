<?php

use Illuminate\Http\Request;

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::post('migrate:fresh', function (){
    return Artisan::call('migrate:fresh');
});

Route::get('me', 'AuthController@me');
Route::post('logout', 'AuthController@logout');

Route::resource('user', 'UserController');
Route::resource('customer', 'CustomerController');

Route::post('update_password/{id}', 'CustomerController@update_password');

Route::resource('customer_group', 'CustomerCategoryController');
Route::resource('role', 'RoleController');
Route::resource('state', 'StateController');
Route::resource('status', 'StatusController');
Route::resource('category', 'CategoryController');


Route::resource('product', 'ProductController');

Route::resource('feedback', 'FeedbackController');


Route::post('product/{id}', 'ProductController@update');
Route::delete('product/{id}', 'ProductController@destroy');

Route::resource('order', 'OrderController');

Route::post('status_change', 'OrderController@status_change');