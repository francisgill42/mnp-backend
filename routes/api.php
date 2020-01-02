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
Route::resource('coldstorage', 'ColdstorageController');
Route::resource('driver', 'DriverController');
Route::resource('maintenance', 'MaintenanceController');

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
Route::get('order_by_user', 'OrderController@get_orders_by_user');
Route::get('processing_orders_by_coldstorage', 'OrderController@get_processing_orders_by_coldstorage');
Route::get('orders_by_coldstorage', 'OrderController@get_orders_by_coldstorage');
Route::post('change_order_item', 'OrderController@change_order_item');
Route::get('get_drivers', 'OrderController@select_drivers');


Route::resource('stock', 'StockController');