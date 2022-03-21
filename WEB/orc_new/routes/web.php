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

Route::get('/', function () {
    return redirect()->route('home');
	//view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('logout', function(){ 
	Auth::logout();
	return redirect()->route('welcome');
});

//Пользователи
Route::get('/users', 'UserController@index')->name('user.main');
Route::get('/create_user', 'UserController@create')->name('user.create');
Route::post('/save_user', 'UserController@store')->name('user.save');
Route::get('/edit_user/{id}', 'UserController@edit')->name('user.edit');
Route::post('/update_user/{id}', 'UserController@update')->name('user.update');
Route::get('/delete_user/{id}', 'UserController@destroy')->name('user.delete');

//Контрагенты
Route::get('/counterparties', 'CounterpartieController@index')->name('counterpartie.main');
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
Route::get('/delete_employee_counterpartie/{id_employee}', 'CounterpartieController@delete_employee')->name('counterpartie.delete_employee');

//Кураторы
Route::get('/curators', 'CuratorController@index')->name('curator.main');
Route::get('/create_curator', 'CuratorController@create')->name('curator.create');
Route::post('/save_curator', 'CuratorController@store')->name('curator.save');
Route::get('/edit_curator/{id}', 'CuratorController@edit')->name('curator.edit');
Route::post('/update_curator/{id}', 'CuratorController@update')->name('curator.update');
Route::get('/delete_curator/{id}', 'CuratorController@destroy')->name('curator.delete');

//Элементы
Route::get('/elements', 'ElementController@index')->name('element.main');
Route::get('/create_element', 'ElementController@create')->name('element.create');
Route::post('/store_element', 'ElementController@store')->name('element.save');
Route::get('/edit_element/{id}', 'ElementController@edit')->name('element.edit');
Route::post('/update_element/{id}', 'ElementController@update')->name('element.update');
Route::get('/delete_element/{id}', 'ElementController@destroy')->name('element.delete');

//Комплектующие элементы
Route::get('/ten/elements', 'ComponentElementController@index')->name('ten.element_main');
Route::get('/ten/create_element', 'ComponentElementController@create')->name('ten.element_create');
Route::post('/ten/store_element', 'ComponentElementController@store')->name('ten.element_save');
Route::get('/ten/edit_element/{id}', 'ComponentElementController@edit')->name('ten.element_edit');
Route::post('/ten/update_element/{id}', 'ComponentElementController@update')->name('ten.element_update');
Route::get('/ten/delete_element/{id}', 'ComponentElementController@destroy')->name('ten.element_delete');

//Партии комплектующих элементов (склады)
Route::get('/ten/party_elements', 'ComponentElementPartyController@index')->name('ten.party_element_main');
Route::get('/ten/create_party_element', 'ComponentElementPartyController@create')->name('ten.party_element_create');
Route::post('/ten/store_party_element', 'ComponentElementPartyController@store')->name('ten.party_element_save');
Route::get('/ten/edit_party_element/{id}', 'ComponentElementPartyController@edit')->name('ten.party_element_edit');
Route::post('/ten/update_party_element/{id}', 'ComponentElementPartyController@update')->name('ten.party_element_update');
Route::get('/ten/delete_party_element/{id}', 'ComponentElementPartyController@destroy')->name('ten.party_element_delete');

//Вид испытания
Route::get('/view_work_elements', 'ViewWorkElementController@index')->name('view_work.element.main');
Route::get('/create_view_work_element', 'ViewWorkElementController@create')->name('view_work.element.create');
Route::post('/store_view_work_element', 'ViewWorkElementController@store')->name('view_work.element.save');
Route::get('/edit_view_work_element/{id}', 'ViewWorkElementController@edit')->name('view_work.element.edit');
Route::post('/update_view_work_element/{id}', 'ViewWorkElementController@update')->name('view_work.element.update');
Route::get('/delete_view_work_element/{id}', 'ViewWorkElementController@destroy')->name('view_work.element.delete');

//Единицы измерения для второго отдела
Route::get('/second_department_units', 'SecondDepartmentUnitController@index')->name('second_department_unit.main');
Route::get('/create_second_department_unit', 'SecondDepartmentUnitController@create')->name('second_department_unit.create');
Route::post('/store_second_department_unit', 'SecondDepartmentUnitController@store')->name('second_department_unit.save');
Route::get('/edit_second_department_unit/{id}', 'SecondDepartmentUnitController@edit')->name('second_department_unit.edit');
Route::post('/update_second_department_unit/{id}', 'SecondDepartmentUnitController@update')->name('second_department_unit.update');
Route::get('/delete_second_department_unit/{id}', 'SecondDepartmentUnitController@destroy')->name('second_department_unit.delete');

//Калибры для второго отдела
Route::get('/second_department_calibers', 'SecondDepartmentCaliberController@index')->name('second_department_caliber.main');
Route::get('/create_second_department_caliber', 'SecondDepartmentCaliberController@create')->name('second_department_caliber.create');
Route::post('/store_second_department_caliber', 'SecondDepartmentCaliberController@store')->name('second_department_caliber.save');
Route::get('/edit_second_department_caliber/{id}', 'SecondDepartmentCaliberController@edit')->name('second_department_caliber.edit');
Route::post('/update_second_department_caliber/{id}', 'SecondDepartmentCaliberController@update')->name('second_department_caliber.update');
Route::get('/delete_second_department_caliber/{id}', 'SecondDepartmentCaliberController@destroy')->name('second_department_caliber.delete');

//Наименование(тип) элемента для второго отдела
Route::get('/second_department_name_element', 'SecondDepartmentNameElementController@index')->name('second_department_name_element.main');
Route::get('/create_second_department_name_element', 'SecondDepartmentNameElementController@create')->name('second_department_name_element.create');
Route::post('/store_second_department_name_element', 'SecondDepartmentNameElementController@store')->name('second_department_name_element.save');
Route::get('/edit_second_department_name_element/{id}', 'SecondDepartmentNameElementController@edit')->name('second_department_name_element.edit');
Route::post('/update_second_department_name_element/{id}', 'SecondDepartmentNameElementController@update')->name('second_department_name_element.update');
Route::get('/delete_second_department_name_element/{id}', 'SecondDepartmentNameElementController@destroy')->name('second_department_name_element.delete');

//Виды работ
Route::get('/view_work', 'ViewWorkController@index')->name('view_work.main');
Route::get('/create_view_work', 'ViewWorkController@create')->name('view_work.create');
Route::post('/store_view_work', 'ViewWorkController@store')->name('view_work.save');
Route::get('/edit_view_work/{id}', 'ViewWorkController@edit')->name('view_work.edit');
Route::post('/update_view_work/{id}', 'ViewWorkController@update')->name('view_work.update');
Route::get('/delete_view_work/{id}', 'ViewWorkController@destroy')->name('view_work.delete');

//Виды договоров/контрактов
Route::get('/view_contract', 'ViewContractController@index')->name('view_contract.main');
Route::get('/create_view_contract', 'ViewContractController@create')->name('view_contract.create');
Route::post('/store_view_contract', 'ViewContractController@store')->name('view_contract.save');
Route::get('/edit_view_contract/{id}', 'ViewContractController@edit')->name('view_contract.edit');
Route::post('/update_view_contract/{id}', 'ViewContractController@update')->name('view_contract.update');
Route::get('/delete_view_contract/{id}', 'ViewContractController@destroy')->name('view_contract.delete');

//Подразделения
Route::get('/all_departments', 'DepartmentController@index')->name('departments.main');
Route::get('/create_department', 'DepartmentController@create')->name('departments.create');
Route::post('/store_department', 'DepartmentController@store')->name('departments.save');
Route::get('/edit_department/{id}', 'DepartmentController@edit')->name('departments.edit');
Route::post('/update_department/{id}', 'DepartmentController@update')->name('departments.update');
Route::get('/delete_department/{id}', 'DepartmentController@destroy')->name('departments.delete');

//Отделы
//Канцелярия
Route::get('/chancery', 'ChanceryController@index')->name('department.chancery');
Route::post('/create_chancery', 'ChanceryController@store')->name('department.chancery.store');
Route::post('/update_chancery/{id}', 'ChanceryController@update')->name('department.chancery.update');
Route::get('/delete_chancery/{id}', 'ChanceryController@destroy')->name('department.chancery.delete');
Route::post('/create_chancery_for_new_application/{id_new_application}', 'ChanceryController@store_for_new_application')->name('department.chancery.store_for_new_application');

// Новые заявки
Route::get('/new_reconciliation_incoming/', 'ReconciliationController@new_incoming')->name('department.reconciliation.new_incoming');
Route::get('/reconciliation_create_new_application/{id_application}', 'ReconciliationController@create_new_application')->name('department.reconciliation.create_new_application');
Route::get('/new_applications/', 'NewApplicationController@index')->name('new_applications.index');
Route::get('/create_new_application/', 'NewApplicationController@create')->name('new_applications.create');
Route::post('/store_new_application/', 'NewApplicationController@store')->name('new_applications.store');
Route::get('/show_new_application/{id_new_application}', 'NewApplicationController@show')->name('new_applications.show');
Route::post('/update_new_application/{id_new_application}', 'NewApplicationController@update')->name('new_applications.update');
Route::post('/create_contract_from_new_application/{id_new_application}', 'NewApplicationController@create_contract')->name('new_applications.create_contract');
Route::get('/copying_new_application/{id_new_application}', 'NewApplicationController@copying')->name('new_applications.copying');
Route::get('/reconciliation_new_application/{id_new_application}', 'NewApplicationController@reconciliation')->name('new_applications.reconciliation');
Route::post('/reconciliation_new_application_store/{id_new_application}', 'NewApplicationController@reconciliation_store')->name('new_applications.reconciliation_store');

Route::post('/reconciliation_new_application_contraction_store/{id_new_application}', 'NewApplicationContractionController@store')->name('new_application_contractions.store');
Route::post('/reconciliation_new_application_contraction_update/{id_new_application_contraction}', 'NewApplicationContractionController@update')->name('new_application_contractions.update');
Route::get('/reconciliation_new_application_contraction_delete/{id_new_application_contraction}', 'NewApplicationContractionController@destroy')->name('new_application_contractions.destroy');

//ПЭО 2
Route::get('/peo', 'ContractController@peo')->name('department.peo');
Route::get('/new_peo_contract', 'ContractController@new_peo_contract')->name('department.peo.new_contract');
Route::post('/store_peo_contract', 'ContractController@store_peo_contract')->name('department.peo.store_contract');
Route::get('/show_peo_contract/{id_contract}', 'ContractController@show_peo_contract')->name('department.peo.show_contract');
Route::post('/update_peo_contract/{id_contract}', 'ContractController@update_peo_contract')->name('department.peo.update_contract');
Route::get('/show_additional_documents/{id_contract}', 'ContractController@show_additional_documents')->name('department.peo.show_additional_documents');
Route::post('/update_position_additional_documents/{id_contract}', 'ContractController@update_position_additional_documents')->name('update_position_additional_documents');
//Согласование новых протоколов и доп. соглашений
Route::get('/reconciliation_additional_document/{id_additional_document}', 'ReconciliationProtocolController@show')->name('reconciliation.additional_document.show');
Route::post('/reconciliation_additional_document_store/{id_additional_document}', 'ReconciliationProtocolController@store')->name('reconciliation.additional_document.store');
Route::get('/reconciliation_additional_document_store/{id_additional_document}/print_reconciliation', 'ReconciliationProtocolController@print_reconciliation')->name('reconciliation.additional_document.print_reconciliation');
//История состояний новыйх протоколов и доп. соглашений
Route::post('/add_new_additional_document_state/{id_}', 'ContractController@new_additional_document_state')->name('department.ekonomic.new_additional_document_state');
//Route::post('/update_state/{id}', 'ContractController@update_state')->name('department.ekonomic.update_state');
//Route::post('/destroy_state/{id}', 'ContractController@destroy_state')->name('department.ekonomic.destroy_state');

//Планово-экономический отдел
Route::get('/ekonomic', 'ContractController@index')->name('department.ekonomic');
Route::get('/ekonomic_sip', 'ContractController@index')->name('department.ekonomic.sip');
Route::get('/new_contract/{number_document}', 'ContractController@create')->name('department.ekonomic.create');
Route::get('/contract_peo/{id}', 'ContractController@show_peo')->name('department.ekonomic.show_peo');
Route::get('/contract_reestr/{id}', 'ContractController@show_reestr')->name('department.ekonomic.show_reestr');
Route::get('/new_contract_reestr', 'ContractController@new_reestr')->name('department.ekonomic.new_reestr');
Route::get('/new_sip_contract_reestr', 'ContractController@new_sip_reestr')->name('department.ekonomic.new_sip_reestr');
Route::get('/new_sip_contract_reestr_2', 'ContractController@new_sip_reestr_2');
Route::get('/new_sip_contract_reestr_3', 'ContractController@new_sip_reestr_3');
Route::get('/new_sip_contract_reestr_4', 'ContractController@new_sip_reestr_4');
Route::get('/new_sip_contract_reestr_5', 'ContractController@new_sip_reestr_5');
Route::post('/create_contract_reestr', 'ContractController@create_reestr')->name('department.ekonomic.create_reestr');
Route::post('/create_sip_contract_reestr', 'ContractController@create_sip_reestr')->name('department.ekonomic.create_sip_reestr');
Route::post('/store_contract/{number_document}', 'ContractController@store')->name('department.ekonomic.store');
Route::post('/update_contract_reestr/{id}', 'ContractController@update_reestr')->name('department.ekonomic.update_reestr');
Route::post('/update_contract_peo/{id}', 'ContractController@update_peo')->name('department.ekonomic.update_peo');
Route::post('/add_new_state/{id}', 'ContractController@new_state')->name('department.ekonomic.new_state');
Route::post('/destroy_state/{id}', 'ContractController@destroy_state')->name('department.ekonomic.destroy_state');
Route::post('/update_state/{id}', 'ContractController@update_state')->name('department.ekonomic.update_state');
Route::get('/delete_contract/{id}', 'ContractController@destroy')->name('department.ekonomic.delete');
//Резолюция
Route::post('/resolution_store/{id}', 'ResolutionController@store')->name('resolution_store');
Route::post('/resolution_update', 'ResolutionController@update')->name('resolution_update');
Route::post('/resolution_counterpartie_store/{id}', 'ResolutionController@store_resol_counterpartie')->name('resolution_counterpartie_store');
Route::get('/resolution_download/{id}', 'ResolutionController@download')->name('resolution_download');
Route::get('/resolution_get', 'ResolutionController@show')->name('resolution_show'); 
Route::get('/resolution_delete/{id}', 'ResolutionController@destroy')->name('resolution_delete'); 
Route::get('/resolution_contract_delete_ajax/{id}', 'ResolutionController@destroy_contract_ajax')->name('resolution_contract_delete_ajax'); 
Route::get('/resolution_additional_document_delete_ajax/{id}', 'ResolutionController@destroy_additional_document_ajax')->name('resolution_additional_document_delete_ajax'); 
//Согласование
Route::get('/reconciliation/', 'ReconciliationController@index')->name('department.reconciliation');
Route::get('/reconciliation_incoming/', 'ReconciliationController@incoming')->name('department.reconciliation.incoming');

Route::get('/reconciliation_document/{number_application}', 'ReconciliationController@document')->name('department.reconciliation.document');
Route::get('/reconciliation_create_document/{id_application}', 'ReconciliationController@create_document')->name('department.reconciliation.create_document');
Route::get('/reconciliation_create_new_document/{id_application}', 'ReconciliationController@create_new_document')->name('department.reconciliation.create_new_document');
Route::get('/reconciliation_destroy_document/{id}', 'ReconciliationController@destroy')->name('department.reconciliation.delete');
Route::post('/reconciliation_document_message/{id}', 'ReconciliationController@reconciliation_document_message_store')->name('department.reconciliation.reconciliation_document_message');
Route::get('/reconciliation_document_message/select/{id}', 'ReconciliationController@reconciliation_document_message_show')->name('department.reconciliation.reconciliation_document_message_show');
Route::get('/reconciliation_document_message/delete/{id_application}', 'ReconciliationController@reconciliation_document_message_destroy')->name('department.reconciliation.reconciliation_document_message_delete');

Route::get('/contract_reconciliation/{id}', 'ReconciliationController@show')->name('department.reconciliation.show');
Route::post('/contract_reconciliation_update/{id}', 'ReconciliationController@update')->name('department.reconciliation.update');
Route::post('/contract_reconciliation_new_process/{id}', 'ReconciliationController@create_process')->name('department.reconciliation.create_process');
Route::get('/contract_reconciliation_end_date/{id}', 'ReconciliationController@end_date')->name('department.reconciliation.end_date');
Route::post('/contract_reconciliation_message/{id}', 'ReconciliationController@reconciliation_contract_message')->name('department.reconciliation.reconciliation_contract_message');
Route::get('/contract_reconciliation_message_delete/{id}', 'ReconciliationController@reconciliation_contract_message_destroy')->name('department.reconciliation.reconciliation_contract_message_delete');
Route::get('/contract_reconciliation/{id}/print_reconciliation', 'ReconciliationController@print_reconciliation')->name('department.reconciliation.print_reconciliation');

Route::get('/application/{id_application}', 'HomeController@show_application')->name('reconciliation.application.show');
Route::get('/reconciliation/application/{id_reconciliation}', 'HomeController@reconciliation_application')->name('reconciliation.application');
Route::post('/reconciliation/application/store/{id_reconciliation}', 'HomeController@store_application')->name('reconciliation.application.store');
Route::post('/reconciliation/application_delete_direction', 'HomeController@delete_direction_application')->name('reconciliation.application.delete_direction');

Route::get('/contract/{id_contract}', 'HomeController@show_contract')->name('reconciliation.contract.show');
Route::get('/reconciliation/contract/{id_reconciliation}', 'HomeController@reconciliation_contract')->name('reconciliation.contract');
Route::post('/reconciliation/contract/store/{id_reconciliation}', 'HomeController@store_contract')->name('reconciliation.contract.store');
Route::post('/reconciliation/contract_delete_direction', 'HomeController@delete_direction_contract')->name('reconciliation.contract.delete_direction');

Route::post('/reconciliation/document/store/{id_application}', 'HomeController@store_document')->name('reconciliation.document.store');
Route::post('/reconciliation/document_delete_direction', 'HomeController@delete_direction_document')->name('reconciliation.document.delete_direction');

Route::post('/reconciliation/contract/checkpoint/store/{id_contract}', 'HomeController@store_checkpoint')->name('department.reconciliation.checkpoint_store');
Route::get('/reconciliation/contract/checkpoint/update/{id_checkpoint}', 'HomeController@update_checkpoint')->name('department.reconciliation.checkpoint_update');

Route::post('/create_protocol/{id}', 'ProtocolController@store')->name('department.reconciliation.store_protocol');

//Отдел управления договорами
Route::get('/management_contracts', 'ManagementController@index')->name('department.management.contracts');
Route::post('/management/new_number/{id_contract}', 'ManagementController@store')->name('department.management.new_number');

Route::get('/contract_new_reestr/{id}', 'ContractController@show_new_reestr')->name('department.ekonomic.contract_new_reestr');
Route::get('/contract_new_reestr/protocols/{id_contract}', 'ProtocolController@show_protocols')->name('department.reestr.show_protocols');
Route::post('/contract_new_reestr/store_protocol/{id_contract}', 'ProtocolController@store_protocol')->name('department.reestr.store_protocol');
Route::post('/contract_new_reestr/update_protocol/{id_protocol}', 'ProtocolController@update_protocol')->name('department.reestr.update_protocol');
Route::get('/contract_new_reestr/delete_protocol/{id_protocol}', 'ProtocolController@destroy')->name('department.reestr.delete_protocol');

Route::get('/contract_new_reestr/additional_agreements/{id_contract}', 'ProtocolController@show_additional_agreements')->name('department.reestr.show_additional_agreements');
Route::post('/contract_new_reestr/store_additional_agreement/{id_contract}', 'ProtocolController@store_additional_agreement')->name('department.reestr.store_additional_agreement');

Route::get('/contract_new_reestr/amount_invoices/{id_contract}', 'ReestrInvoiceController@show')->name('department.reestr.show_amount_invoice');
Route::post('/contract_new_reestr/store_amount_invoices/{id_contract}', 'ReestrInvoiceController@store')->name('department.reestr.store_amount_invoice');
Route::post('/contract_new_reestr/update_amount_invoices/{id_invoice}', 'ReestrInvoiceController@update')->name('department.reestr.update_amount_invoice');

Route::get('/contract_new_reestr/specifies/{id_contract}', 'ReestrSpecifyController@show')->name('department.reestr.show_specify');
Route::post('/contract_new_reestr/store_specifies/{id_contract}', 'ReestrSpecifyController@store')->name('department.reestr.store_specify');
Route::post('/contract_new_reestr/update_specifies/{id_specify}', 'ReestrSpecifyController@update')->name('department.reestr.update_specify');

Route::get('/contract_new_reestr/obligation/{id_contract}', 'ObligationController@show_obligation')->name('department.reestr.show_obligation');
Route::post('/contract_new_reestr/update_obligation/{id_contract}', 'ObligationController@update_obligation')->name('department.reestr.update_obligation');
Route::post('/contract_new_reestr/create_obligation_invoice/{id_contract}', 'ObligationController@create_obligation_invoice')->name('department.reestr.create_obligation_invoice');
Route::post('/contract_new_reestr/update_obligation_invoice/{id_obligation_invoice}', 'ObligationController@update_obligation_invoice')->name('department.reestr.update_obligation_invoice');
Route::get('/contract_new_reestr/delete_obligation_invoice/{id_obligation_invoice}', 'ObligationController@delete_obligation_invoice')->name('department.reestr.delete_obligation_invoice');

Route::post('/contract_new_reestr/search_counterpartie', 'CounterpartieController@search_counterpartie')->name('department.reestr.search_counterpartie');

Route::get('/print_reestr', 'ContractController@print_reestr')->name('department.reestr.print_reestr');

// Реестр (срок действия Д/К)
Route::post('/new_reestr_date_contract/{id_contract}', 'ReestrDateContractController@store')->name('reestr.date_contract.store');
Route::post('/update_reestr_date_contract/{id_date_contract}', 'ReestrDateContractController@update')->name('reestr.date_contract.update');
Route::get('/delete_reestr_date_contract/{id_contract}', 'ReestrDateContractController@destroy')->name('reestr.date_contract.destroy');

// Реестр (срок исполнения обязательств)
Route::post('/new_reestr_date_maturity/{id_contract}', 'ReestrDateMaturityController@store')->name('reestr.date_maturity.store');
Route::post('/update_reestr_date_maturity/{id_date_contract}', 'ReestrDateMaturityController@update')->name('reestr.date_maturity.update');
Route::get('/delete_reestr_date_maturity/{id_contract}', 'ReestrDateMaturityController@destroy')->name('reestr.date_maturity.destroy');

// Реестр (суммы)
Route::post('/new_reestr_amount/{id_contract}', 'ReestrAmountController@store')->name('reestr.amount.store');
Route::post('/update_reestr_amount/{id_date_contract}', 'ReestrAmountController@update')->name('reestr.amount.update');
Route::get('/delete_reestr_amount/{id_contract}', 'ReestrAmountController@destroy')->name('reestr.amount.destroy');

//Финансовый отдел
Route::get('/invoice', 'InvoiceController@index')->name('department.invoice');
Route::get('/contract_invoice/{id}', 'InvoiceController@show_contract')->name('department.contract_invoice.show');
Route::get('/invoice/{id}', 'InvoiceController@show_invoice')->name('department.invoice.show');
Route::get('/new_score/{id}', 'InvoiceController@create_score')->name('department.invoice.create_score');
Route::get('/new_prepayment/{id}', 'InvoiceController@create_prepayment')->name('department.invoice.create_prepayment');
Route::get('/new_invoice/{id}', 'InvoiceController@create_invoice')->name('department.invoice.create_invoice');
Route::get('/new_payment/{id}', 'InvoiceController@create_payment')->name('department.invoice.create_payment');
Route::get('/new_return/{id}', 'InvoiceController@create_return')->name('department.invoice.create_return');
Route::post('/create_invoice', 'InvoiceController@store_invoice')->name('department.invoice.store');
Route::post('/update_invoice/{id}', 'InvoiceController@update')->name('department.invoice.update');
Route::get('/delete_invoice/{id}', 'InvoiceController@destroy')->name('department.invoice.delete');

//Второй отдел
Route::get('/second', 'SecondDepartmentController@index')->name('department.second');
Route::get('/contract_second/{id}', 'SecondDepartmentController@show_contract')->name('department.contract_second.show');
Route::post('/new_second_isp/{id}', 'SecondDepartmentController@create_isp')->name('department.second_isp.create');
Route::post('/update_second_isp/{id}', 'SecondDepartmentController@update_isp')->name('department.second_isp.update');
Route::post('/new_second_sb/{id}', 'SecondDepartmentController@create_sb')->name('department.second_sb.create');
Route::post('/update_second_sb/{id}', 'SecondDepartmentController@update_sb')->name('department.second_sb.update');

Route::post('/new_comment/{id}', 'SecondDepartmentController@new_comment')->name('department.second.new_comment');

Route::get('/delete_second/{id}', 'SecondDepartmentController@destroy')->name('department.second.delete');	//Удаление испытания
Route::get('/delete_second_sb/{id}', 'SecondDepartmentController@destroy_sb')->name('department.second.delete_sb');	//Удаление испытания сборки
Route::get('/delete_second_us/{id}', 'SecondDepartmentController@destroy_us')->name('department.second.delete_us');	//Удаление испытания услуги

Route::post('/new_isp_create/{id_contract}', 'SecondDepartmentController@create_new_isp')->name('department.second.new_isp_create');
Route::post('/new_isp_update/{id_contract}', 'SecondDepartmentController@update_new_isp')->name('department.second.new_isp_update');

Route::get('/second/tour_of_duty/{id_contract}', 'SecondDepartmentController@new_tour_of_duty')->name('department.second.new_tour_of_duty');
Route::get('/second/tour_of_duty_exp/{id_contract}', 'SecondDepartmentController@new_tour_of_duty_exp')->name('department.second.new_tour_of_duty_exp');
Route::get('/second/tour_of_duty_sb/{id_contract}', 'SecondDepartmentController@new_tour_of_duty_sb')->name('department.second.new_tour_of_duty_sb');
Route::get('/second/tour_of_duty_us/{id_contract}', 'SecondDepartmentController@new_tour_of_duty_us')->name('department.second.new_tour_of_duty_us');
Route::post('/second/store_tour_of_duty/{id_contract}', 'SecondDepartmentController@store_tour_of_duty')->name('department.second.store_tour_of_duty');
Route::post('/second/store_tour_of_duty_exp/{id_contract}', 'SecondDepartmentController@store_tour_of_duty_exp')->name('department.second.store_tour_of_duty_exp');
Route::post('/second/store_tour_of_duty_sb/{id_contract}', 'SecondDepartmentController@store_tour_of_duty_sb')->name('department.second.store_tour_of_duty_sb');
Route::post('/second/store_tour_of_duty_us/{id_contract}', 'SecondDepartmentController@store_tour_of_duty_us')->name('department.second.store_tour_of_duty_us');
Route::get('/second/edit_tour_of_duty/{id_second_dep_duty}', 'SecondDepartmentController@edit_tour_of_duty')->name('department.second.edit_tour_of_duty');
Route::get('/second/edit_tour_of_duty_exp/{id_second_dep_duty}', 'SecondDepartmentController@edit_tour_of_duty_exp')->name('department.second.edit_tour_of_duty_exp');
Route::get('/second/edit_tour_of_duty_sb/{id_second_dep_duty}', 'SecondDepartmentController@edit_tour_of_duty_sb')->name('department.second.edit_tour_of_duty_sb');
Route::get('/second/edit_tour_of_duty_us/{id_second_dep_duty}', 'SecondDepartmentController@edit_tour_of_duty_us')->name('department.second.edit_tour_of_duty_us');
Route::post('/second/update_tour_of_duty/{id_second_dep_duty}', 'SecondDepartmentController@update_tour_of_duty')->name('department.second.update_tour_of_duty');
Route::post('/second/update_tour_of_duty_exp/{id_second_dep_duty}', 'SecondDepartmentController@update_tour_of_duty_exp')->name('department.second.update_tour_of_duty_exp');
Route::post('/second/update_tour_of_duty_sb/{id_second_dep_duty}', 'SecondDepartmentController@update_tour_of_duty_sb')->name('department.second.update_tour_of_duty_sb');
Route::post('/second/update_tour_of_duty_us/{id_second_dep_duty}', 'SecondDepartmentController@update_tour_of_duty_us')->name('department.second.update_tour_of_duty_us');

Route::post('/print_second', 'SecondDepartmentController@print_report')->name('department.second.print_report');

//Акты для испытаний во втором отделе
Route::get('/second/show_all_acts/{id_second_tour}', 'SecondDepartmentActController@show')->name('department.second.show_all_acts');
Route::get('/second/create_new_act/{id_second_tour}', 'SecondDepartmentActController@create')->name('department.second.create_act');
Route::post('/second/store_new_act/{id_second_tour}', 'SecondDepartmentActController@store')->name('department.second.store_act');
Route::get('/second/edit_act/{id_second_act}', 'SecondDepartmentActController@edit')->name('department.second.edit_act');
Route::post('/second/update_act/{id_second_act}', 'SecondDepartmentActController@update')->name('department.second.update_act');
Route::get('/second/delete_act/{id_second_act}', 'SecondDepartmentActController@destroy')->name('department.second.delete_act');

//Акты для сборки во втором отделе
Route::get('/second/show_all_acts_sb/{id_second_tour}', 'SecondDepartmentActController@show_sb')->name('department.second.show_all_acts_sb');
Route::get('/second/create_new_act_sb/{id_second_tour}', 'SecondDepartmentActController@create_sb')->name('department.second.create_act_sb');
Route::post('/second/store_new_act_sb/{id_second_tour}', 'SecondDepartmentActController@store_sb')->name('department.second.store_act_sb');

//Акты для услуг во втором отделе
Route::get('/second/show_all_acts_us/{id_second_tour}', 'SecondDepartmentActController@show_us')->name('department.second.show_all_acts_us');
Route::get('/second/create_new_act_us/{id_second_tour}', 'SecondDepartmentActController@create_us')->name('department.second.create_act_us');
Route::post('/second/store_new_act_us/{id_second_tour}', 'SecondDepartmentActController@store_us')->name('department.second.store_act_us');

//Акты для контракта
Route::post('/second/store_new_contract_act/{id_contract}', 'SecondDepartmentActController@store_for_contract')->name('department.second.store_contract_act');

//Весь реестр
Route::get('/all_reestr', 'ContractController@show_all_reestr')->name('reestr.show');

//Десятый отдел
Route::get('/ten', 'ComponentController@index')->name('department.ten');
Route::get('/ten/document_components/{id_document}', 'ComponentController@show')->name('ten.document_components');
Route::get('/ten/start_new_reconciliation/{id_application}', 'ComponentController@start_new_reconciliation')->name('ten.start_new_reconciliation');
Route::get('/ten/create_component', 'ComponentController@create')->name('ten.create_component');
Route::post('/ten/save_component', 'ComponentController@store_component')->name('ten.store_component');
Route::post('/ten/new_component/{id_contract}', 'ComponentController@store')->name('ten.store_new_component');
Route::post('/ten/old_component/{id_contract}', 'ComponentController@store_old_component')->name('ten.store_old_component');
Route::get('/ten/component_pack/{id_component}', 'ComponentController@edit')->name('ten.edit_component_pack');
Route::post('/ten/edit_component/{id_component}', 'ComponentController@update')->name('ten.update_component');
Route::get('/ten/delete_component/{id_component}', 'ComponentController@destroy')->name('ten.delete_component');
Route::get('/ten/delete_pack/{id_pack}', 'ComponentController@destroy_pack')->name('ten.delete_pack');
Route::get('/ten/chose_all_component/{id_document}', 'ComponentController@chose_all_component')->name('ten.chose_all_component');
Route::post('/ten/chose_component/{id_document}', 'ComponentController@chose_component')->name('ten.chose_component');
Route::get('/ten/contract_components/{id_contract}', 'ComponentController@show_contract')->name('ten.show_contract');
Route::get('/ten/chose_all_contract/{id_document}', 'ComponentController@chose_all_contract')->name('ten.chose_all_contract');
Route::post('/ten/chose_contract/{id_component}', 'ComponentController@chose_contract')->name('ten.chose_contract');
Route::get('/ten/delete_contract/{id_component_contract}', 'ComponentController@destroy_component_contract')->name('ten.delete_component_contract');
Route::get('/ten/create_contract/{id_pack}', 'ComponentController@create_contract')->name('ten.create_contract');
Route::post('/ten/save_contract/{id_pack}', 'ComponentController@store_contract')->name('ten.save_contract');

Route::get('/ten/small_component/{id_component}', 'ComponentController@edit_small_component')->name('ten.edit_small_component');

Route::get('/ten/pack/change_complete/{id_pack}', 'ComponentController@change_complete')->name('ten.change_complete');

//Новый десятый отдел
Route::get('/new_ten', 'ComponentController@index_2')->name('department.ten.new');

//Руководство
Route::get('/leadership', 'LeadershipDepartmentController@index')->name('department.leadership');
Route::get('/print_peo', 'LeadershipDepartmentController@peo')->name('department.leadership.peo');
Route::get('/print_peo_noexecute', 'LeadershipDepartmentController@peoNoExecute')->name('department.leadership.peoNoExecute');
Route::get('/peo_backpack', 'LeadershipDepartmentController@peoBackpack')->name('department.leadership.peoBackpack');
Route::get('/peo_act_complete', 'LeadershipDepartmentController@peo_act_complete')->name('department.leadership.peo_act_complete');
Route::get('/peo_isp_period_complete', 'LeadershipDepartmentController@peo_isp_period_complete')->name('department.leadership.peo_isp_period_complete');
Route::get('/print_invoice', 'LeadershipDepartmentController@invoice')->name('department.leadership.invoice');
Route::get('/print_duty', 'LeadershipDepartmentController@duty')->name('department.leadership.duty');
Route::get('/build_report', 'LeadershipDepartmentController@create_report')->name('department.leadership.create_report');
Route::post('/print_report', 'LeadershipDepartmentController@print_report')->name('department.leadership.print_report');

//Архив
Route::get('/archive', 'ArchiveController@index')->name('archive.main');

//Журнал событий
Route::get('/journal', 'JournalController@index')->name('journal.main');
Route::get('/journal_report', 'JournalController@report')->name('journal.report');
Route::get('/journal_contract/{id_contract}', 'JournalController@contract')->name('journal.contract');

//Древо заявки
Route::get('/tree_map/{id}', 'TreeController@show')->name('tree_map.show');
//Древо комплектации
Route::get('/tree_map_component/{id_component}', 'TreeController@show_component')->name('tree_map.show_component');
//Древо договора
Route::get('/tree_map_contract/{id_contract}', 'TreeController@show_contract')->name('tree_map.show_contract');

//Администрирование
Route::get('/administrator', 'AdministratorController@index')->name('administrator.main');

//Инструкция
Route::get('/instruction', 'InstructionController@index')->name('instruction');

//Подпись
Route::get('/signature', 'SignatureController@index')->name('signature.main');
Route::post('/signature/save', 'SignatureController@store')->name('signature.store_signature');

//Загрузчики
Route::get('/loaders', 'LoaderController@index')->name('loader.main');

//Страница примера
Route::get('/primer', function(){ return view('primer'); })->name('primer');
Route::get('/primer2', function(){ return view('primer2'); })->name('primer2');

//Гет АйПи
Route::get('/get_ip', function(){
	dd(\Request::ip());
});

//Помощник
Route::get('/download_helper', 'HelperController@download')->name('download_helper');
Route::get('/get_all_users', 'HelperController@all_users')->name('get_all_users');
Route::get('/all_select_commands', 'HelperController@all_select_commands')->name('all_select_commands');
Route::get('/all_unsigning_contract', 'HelperController@all_unsigning_contract')->name('all_unsigning_contract');
Route::get('/all_department_unsigning_contract', 'HelperController@all_department_unsigning_contract')->name('all_department_unsigning_contract');

//ORC REPORTS
Route::get('/check_version_orc_reports', 'ReportsController@check_version')->name('reports.check_version_orc_reports');
Route::get('/download_orc_reports', 'ReportsController@download')->name('reports.download_orc_reports');
Route::get('/second_department_print_reports', 'ReportsController@second_department_print')->name('reports.second_department_print');
Route::any('/unprotected/get_orc_report_oud', 'ReportsController@report_oud')->name('reports.report_oud');
Route::any('/unprotected/get_orc_report_peo_no_execute', 'ReportsController@report_peo_no_execute')->name('reports.report_peo_no_execute');
//Для получение БД для отчетов
Route::get('/reports/view_sip_contracts', 'ReportsController@view_sip_contracts');
Route::get('/reports/counterparties_sip', 'ReportsController@counterparties_sip');
Route::get('/reports/departments', 'ReportsController@departments');

//AJAX запрос
Route::get('/setNewCookie', 'AjaxController@set_cookie')->name('ajax.set_cookie');
Route::get('/setCompleteContract/{id_contract}', 'AjaxController@set_complete_contract')->name('ajax.set_complete_contract');

//Создание отчетов с помощью C#
Route::get('/storage_reports', 'CSharpController@index')->name('sharp.storage');
Route::post('/print_report_c_sharp', 'CSharpController@create')->name('sharp.create');