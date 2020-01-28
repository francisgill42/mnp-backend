<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProductController@product_form');
Route::post('/add-product', 'ProductController@add_product');

/*Route::get('/update_req', function(){
    return view('update_req');
});
Route::post('request_update', 'MaintenanceUserController@request_update');*/

Route::get('/emailtemplate', 'OrderController@send_email');
Route::get('/export', 'OrderController@export');
Route::get('/pdf_export', 'OrderController@pdf_export');