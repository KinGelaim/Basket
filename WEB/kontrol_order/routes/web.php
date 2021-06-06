<?php

use Illuminate\Support\Facades\Route;

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

//Авторизация, регистрация
Auth::routes();

Route::middleware('throttle:60,1')->group(function(){
	//Основные страницы
	Route::get('/', 'HomeController@index')->name('welcome');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

//Приказы
Route::get('/orders', 'OrderController@index')->name('orders.main');
Route::get('/new_order', 'OrderController@create')->name('orders.new_order');
Route::post('/store_order', 'OrderController@store')->name('orders.store_order');
Route::get('/order/{id_order}', 'OrderController@show')->name('orders.show_order');
Route::post('/update_order/{id_order}', 'OrderController@update')->name('orders.update_order');
Route::get('/delete_order/{id_order}', 'OrderController@delete')->name('orders.delete_order');

Route::post('/resolution_store/{id_order}', 'ResolutionController@store')->name('resolution_store');
Route::get('/resolution_delete/{id_resolution}', 'ResolutionController@destroy')->name('resolution_delete');

//Уведомления
Route::get('/print_notify', 'NotifycationController@print_notify')->name('print_notify');

//Отчёты
Route::get('/print_report', 'PrintController@print_report')->name('print_report');

//PDF
Route::get('/print_report_pdf', 'PDFController@print_report_pdf')->name('print_report_pdf');

//Перенос сроков
Route::post('/store_postponement/{id_order}', 'PostponementController@store')->name('store_postponement');

//Архив
Route::get('/archive', 'ArchiveController@index')->name('archive.main');

//Журнал
Route::get('/journal', 'JournalController@index')->name('journal.main');

//---------Администрирование---------
Route::get('/administration', 'AdministrationController@index')->name('administration.main');

//Пользователи
Route::get('/users', 'UserController@index')->name('user.main');
Route::get('/create_user', 'UserController@create')->name('user.create');
Route::post('/save_user', 'UserController@store')->name('user.save');
Route::get('/edit_user/{id}', 'UserController@edit')->name('user.edit');
Route::post('/update_user/{id}', 'UserController@update')->name('user.update');
Route::get('/delete_user/{id}', 'UserController@destroy')->name('user.delete');

//Роли
Route::get('/roles', 'RoleController@index')->name('role.main');
Route::get('/create_role', 'RoleController@create')->name('role.create');
Route::post('/save_role', 'RoleController@store')->name('role.save');
Route::get('/edit_role/{id}', 'RoleController@edit')->name('role.edit');
Route::post('/update_role/{id}', 'RoleController@update')->name('role.update');
Route::get('/delete_role/{id}', 'RoleController@destroy')->name('role.delete');

//Контрагенты
Route::get('/counterparties', 'CounterpartieController@index')->name('counterpartie.main');
Route::get('/create_counterpartie', 'CounterpartieController@create')->name('counterpartie.create');
Route::post('/save_counterpartie', 'CounterpartieController@store')->name('counterpartie.save');
Route::get('/edit_counterpartie/{id}', 'CounterpartieController@edit')->name('counterpartie.edit');
Route::post('/update_counterpartie/{id}', 'CounterpartieController@update')->name('counterpartie.update');
Route::get('/delete_counterpartie/{id}', 'CounterpartieController@destroy')->name('counterpartie.delete');

//Типы документов
Route::get('/type_documents', 'TypeDocumentController@index')->name('type_document.main');
Route::get('/create_type_document', 'TypeDocumentController@create')->name('type_document.create');
Route::post('/save_type_document', 'TypeDocumentController@store')->name('type_document.save');
Route::get('/edit_type_document/{id}', 'TypeDocumentController@edit')->name('type_document.edit');
Route::post('/update_type_document/{id}', 'TypeDocumentController@update')->name('type_document.update');
Route::get('/delete_type_document/{id}', 'TypeDocumentController@destroy')->name('type_document.delete');

//Периоды контроля
Route::get('/kontrol_periods', 'KontrolPeriodController@index')->name('kontrol_period.main');
Route::get('/create_kontrol_period', 'KontrolPeriodController@create')->name('kontrol_period.create');
Route::post('/save_kontrol_period', 'KontrolPeriodController@store')->name('kontrol_period.save');
Route::get('/edit_kontrol_period/{id}', 'KontrolPeriodController@edit')->name('kontrol_period.edit');
Route::post('/update_kontrol_period/{id}', 'KontrolPeriodController@update')->name('kontrol_period.update');
Route::get('/delete_kontrol_period/{id}', 'KontrolPeriodController@destroy')->name('kontrol_period.delete');

//Отчёты внешнии
Route::get('/download_kontrol_period_reports', 'ReportController@download')->name('download_kontrol_period_reports');
Route::get('/report_notify', 'ReportController@report_notify')->name('report_notify');
Route::get('/report_print', 'ReportController@report_print')->name('report_print');