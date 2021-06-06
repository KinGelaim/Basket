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

//Контрагенты
Route::get('/', 'CounterpartieController@index')->name('counterpartie.main');
Route::get('/create_counterpartie', 'CounterpartieController@create')->name('counterpartie.create');
Route::post('/save_counterpartie', 'CounterpartieController@store')->name('counterpartie.save');
Route::get('/edit_counterpartie/{id}', 'CounterpartieController@edit')->name('counterpartie.edit');
Route::get('/new_edit_counterpartie/{id}', 'CounterpartieController@new_edit')->name('counterpartie.new_edit');
Route::post('/update_counterpartie/{id}', 'CounterpartieController@update')->name('counterpartie.update');
Route::get('/delete_counterpartie/{id}', 'CounterpartieController@destroy')->name('counterpartie.delete');

Route::post('/save_title_document/{id_counterpartie}', 'CounterpartieController@store_title_document')->name('counterpartie.save_title_document');
Route::post('/update_title_document/{id_title_document}', 'CounterpartieController@update_title_document')->name('counterpartie.update_title_document');
Route::post('/save_bank_detail/{id_counterpartie}', 'CounterpartieController@store_bank_detail')->name('counterpartie.save_bank_detail');
Route::post('/update_bank_detail/{id_bank_detail}', 'CounterpartieController@update_bank_detail')->name('counterpartie.update_bank_detail');

Route::get('/reestr_counterparie/{id_counterpartie}', 'CounterpartieController@show_reestr')->name('counterpartie.show_reestr');

Route::post('/store_employee_counterpartie/{id_counterpartie}', 'CounterpartieController@store_employee')->name('counterpartie.store_employee');
Route::post('/update_employee_counterpartie/{id_employee}', 'CounterpartieController@update_employee')->name('counterpartie.update_employee');
Route::post('/swap_employee_counterpartie/', 'CounterpartieController@swap_employee')->name('counterpartie.swap_employee');
Route::get('/delete_employee_counterpartie/{id_employee}', 'CounterpartieController@delete_employee')->name('counterpartie.delete_employee');

Route::get('/report', 'CounterpartieController@report')->name('counterpartie.report');