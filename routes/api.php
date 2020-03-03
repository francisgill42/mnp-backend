<?php

use Illuminate\Http\Request;


Route::get('test', function (){
    echo "hi api";
});


Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::post('admin_login', 'AdminController@login');

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
Route::resource('city', 'CityController');
Route::resource('status', 'StatusController');
Route::resource('category', 'CategoryController');


Route::resource('product', 'ProductController');

Route::resource('feedback', 'FeedbackController');


Route::post('product/{id}', 'ProductController@update');
Route::delete('product/{id}', 'ProductController@destroy');

Route::resource('order', 'OrderController');
Route::get('search_order/{q}', 'OrderController@search_order');

Route::post('status_change', 'OrderController@status_change');
Route::get('order_by_user', 'OrderController@get_orders_by_user');
Route::get('processing_orders_by_coldstorage', 'OrderController@get_processing_orders_by_coldstorage');
Route::get('orders_by_coldstorage', 'OrderController@get_orders_by_coldstorage');
Route::post('change_order_item', 'OrderController@change_order_item');
Route::post('add_order_item', 'OrderController@add_order_item');
Route::get('get_drivers', 'OrderController@select_drivers');
Route::get('assigned_orders_to_driver', 'OrderController@get_assigned_orders_to_driver');
Route::get('delivered_orders_by_driver', 'OrderController@get_delivered_orders_by_driver');


Route::resource('stock', 'StockController');
Route::resource('stock_adjustment', 'StockAdjController');

Route::resource('discount', 'DiscountController');
Route::post('update_discount', 'DiscountController@update_discount');

Route::resource('maintenanceuser', 'MaintenanceUserController');
Route::post('request_status_change', 'MaintenanceUserController@request_status_change');
Route::post('request_update', 'MaintenanceUserController@request_update');

Route::get('counters', 'AdminController@counters');

Route::get('daily_orders', 'OrderController@daily_orders');
Route::get('weekly_orders', 'OrderController@weekly_orders');
Route::get('monthly_orders', 'OrderController@monthly_orders');

Route::get('/export', 'OrderController@export');
Route::get('/export_orders', 'OrderController@export_orders');
Route::get('/recent_orders', 'OrderController@recent_orders');

Route::get('/filter_listing', 'OrderController@filter_listing');
Route::get('/orders_by_products', 'OrderController@orders_by_products');
Route::get('/orders_by_customers', 'OrderController@orders_by_customers');
Route::get('/orders_by_drivers', 'OrderController@orders_by_drivers');
Route::get('/orders_by_states', 'OrderController@orders_by_states');
Route::get('/orders_by_cities', 'OrderController@orders_by_cities');

Route::post('/import', 'ExcelController@import_data');