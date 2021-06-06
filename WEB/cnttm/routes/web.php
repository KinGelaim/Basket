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
	Route::get('/', function () {
		return view('home');
	});
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/centr', function () {
    return view('centr');
})->name('centr');

//Педагоги
Route::get('/prepods_old', function () {
    return view('prepods_old');
})->name('prepods_old');
Route::get('/prepods', 'EducatorController@show')->name('prepods');
Route::get('/prepods/main', 'EducatorController@index')->name('educator.main');
Route::get('/create_prepod', 'EducatorController@create')->name('educator.create');
Route::post('/save_prepod', 'EducatorController@store')->name('educator.save');
Route::get('/edit_prepod/{id}', 'EducatorController@edit')->name('educator.edit');
Route::post('/update_prepod/{id}', 'EducatorController@update')->name('educator.update');
Route::get('/delete_prepod/{id}', 'EducatorController@destroy')->name('educator.delete');

//Достижения
Route::get('/achievements', function(){
	return view('achievements');
})->name('achievements');

//Перспективы
Route::get('/perspectives', function(){
	return view('perspectives');
})->name('perspectives');

//Обратная связь
Route::get('/contact', 'MessageController@index')->name('contact');
Route::get('/show_message', 'MessageController@show')->name('contact.show_message');
Route::post('/store_message', 'MessageController@store')->name('contact.store_message');

//Страница аттестации
Route::get('/control', 'SchoolchildrenTestController@index')->name('control');

//---------Администрирование---------
Route::get('/administration', 'AdministrationController@index')->name('administration.main');

//Пользователи
Route::get('/users', 'UserController@index')->name('user.main');
Route::get('/create_user', 'UserController@create')->name('user.create');
Route::post('/save_user', 'UserController@store')->name('user.save');
Route::get('/edit_user/{id}', 'UserController@edit')->name('user.edit');
Route::post('/update_user/{id}', 'UserController@update')->name('user.update');
Route::get('/delete_user/{id}', 'UserController@destroy')->name('user.delete');

Route::post('/update_user_email/{id}', 'UserController@update_email')->name('user.update_email');

//Роли
Route::get('/roles', 'RoleController@index')->name('role.main');
Route::get('/create_role', 'RoleController@create')->name('role.create');
Route::post('/save_role', 'RoleController@store')->name('role.save');
Route::get('/edit_role/{id}', 'RoleController@edit')->name('role.edit');
Route::post('/update_role/{id}', 'RoleController@update')->name('role.update');
Route::get('/delete_role/{id}', 'RoleController@destroy')->name('role.delete');

//Лаборатории
Route::get('/laboratories', 'LaboratoryController@index')->name('laboratory.main');
Route::get('/create_laboratory', 'LaboratoryController@create')->name('laboratory.create');
Route::post('/save_laboratory', 'LaboratoryController@store')->name('laboratory.save');
Route::get('/edit_laboratory/{id}', 'LaboratoryController@edit')->name('laboratory.edit');
Route::post('/update_laboratory/{id}', 'LaboratoryController@update')->name('laboratory.update');
Route::get('/delete_laboratory/{id}', 'LaboratoryController@destroy')->name('laboratory.delete');

//Группы
Route::get('/groups', 'GroupController@index')->name('group.main');
Route::get('/create_group', 'GroupController@create')->name('group.create');
Route::post('/save_group', 'GroupController@store')->name('group.save');
Route::get('/edit_group/{id}', 'GroupController@edit')->name('group.edit');
Route::post('/update_group/{id}', 'GroupController@update')->name('group.update');
Route::get('/delete_group/{id}', 'GroupController@destroy')->name('group.delete');

//Журнал посещения (накладывается на группу)
Route::get('/journal/{id_group}', 'JournalController@journals')->name('group.journal');
Route::get('/journal_create/{id_group}', 'JournalController@create')->name('journal.create');
Route::post('/journal_save/{id_group}', 'JournalController@save')->name('journal.save');
Route::get('/journal_show/{id_journal}', 'JournalController@show')->name('journal.show');
Route::get('/journal_delete/{id_journal}', 'JournalController@delete')->name('journal.delete');

Route::post('/journal_visit_add/{id_journal}', 'JournalController@add_visit')->name('journal.visit.add');

//Финансы
Route::get('/schoolchildren_finance/{id_schoolchildren}', 'FinanceController@show')->name('schoolchildren.finance');
Route::post('/finance_save/{id_user}', 'FinanceController@store')->name('finance.save');

//Оценки
Route::get('/journal_state', 'JournalStateController@index')->name('journal_state.main');
Route::get('/journal_state_create', 'JournalStateController@create')->name('journal_state.create');
Route::post('/journal_state_save', 'JournalStateController@save')->name('journal_state.save');
Route::get('/journal_state_delete', 'JournalStateController@delete')->name('journal_state.delete');

//Ученики
Route::get('/schoolchildrens', 'SchoolchildrenController@index')->name('schoolchildren.main');
Route::get('/create_schoolchildren', 'SchoolchildrenController@create')->name('schoolchildren.create');
Route::post('/save_schoolchildren', 'SchoolchildrenController@store')->name('schoolchildren.save');
Route::get('/edit_schoolchildren/{id}', 'SchoolchildrenController@edit')->name('schoolchildren.edit');
Route::post('/update_schoolchildren/{id}', 'SchoolchildrenController@update')->name('schoolchildren.update');
Route::get('/delete_schoolchildren/{id}', 'SchoolchildrenController@destroy')->name('schoolchildren.delete');
Route::get('/redirect_schoolchildren/{id_schoolchildren}', 'SchoolchildrenController@redirect')->name('schoolchildren.redirect');
Route::get('/redirect_group/{id_schoolchildren}', 'SchoolchildrenController@redirect_group')->name('schoolchildren.redirect_group');

//Тесты
Route::get('/tests', 'TestController@index')->name('test.main');
Route::get('/create_test', 'TestController@create')->name('test.create');
Route::post('/save_test', 'TestController@store')->name('test.save');
Route::get('/show_test/{id}', 'TestController@show')->name('test.show');	//Отображения вопросов для конкретного теста
Route::get('/all_show_test/{id}', 'TestController@all_show_test')->name('test.all_show_test');	//Отображения вопросов для конкретного теста так, как видит их ученик
Route::get('/edit_test/{id}', 'TestController@edit')->name('test.edit');
Route::post('/update_test/{id}', 'TestController@update')->name('test.update');
Route::get('/delete_test/{id}', 'TestController@destroy')->name('test.delete');
//Направление тестов
Route::get('/redirect_group_test/{id_test}', 'TestController@redirect_group')->name('test.redirect_group');
Route::get('/redirect_schoolchildren_test/{id_test}', 'TestController@redirect_schoolchildren')->name('test.redirect_schoolchildren');
//Прохождение тестов (для учеников)
Route::get('/complete_test/{id_schoolchildren}/{id_test}', 'TestController@complete')->name('test.complete');
//Список тестов для ученика (со стороны преподавателя)
Route::get('/show_test_schoolchildren/{id_schoolchildren}', 'TestController@show_test_schoolchildren')->name('test.show_test_schoolchildren');
//Проверка теста (для преподов)
Route::get('/complete_answer_test/{id_schoolchildren}/{id_test}', 'TestController@complete_answer')->name('test.complete_answer');

//Вопросы
Route::get('/create_question/{id_test}', 'QuestionController@create')->name('question.create');
Route::post('/save_question/{id_test}', 'QuestionController@store')->name('question.save');
Route::get('/edit_question/{id}', 'QuestionController@edit')->name('question.edit');
Route::post('/update_question/{id}', 'QuestionController@update')->name('question.update');
Route::get('/delete_question/{id}', 'QuestionController@destroy')->name('question.delete');

//Ответы
Route::get('/schoolchildrens_answer', 'AnswerController@index')->name('administration.answer.main_answers');
Route::post('/save_answer/{id_schoolchildren}/{id_test}', 'AnswerController@store')->name('answer.save');
Route::post('/administration_save_answer/{id_schoolchildren}/{id_test}', 'AnswerController@update')->name('administration.answer.save');

//Методические материалы
Route::get('/teaching_materials', 'TeachingMaterialController@index')->name('teaching_materials');
Route::get('/teaching_materials/history', 'TeachingMaterialController@history')->name('teaching_materials.history');
Route::get('/teaching_materials/tb', 'TeachingMaterialController@tb')->name('teaching_materials.tb');
Route::get('/teaching_materials/radio', 'TeachingMaterialController@radio')->name('teaching_materials.radio');

//Вьюшки для направлений (лабораторий)
Route::get('/laboratories/modeling_programming', function(){
	return view('laboratories.modeling_programming');	//Лаборатория Компьютерные технологии и программы (3д моделинг)
})->name('laboratories.modeling_programming');

Route::get('/laboratories/robot', function(){
	return view('laboratories.robot');	//Лаборатория Компьютерные технологии и робототехника
})->name('laboratories.robot');

Route::get('/laboratories/radio', function(){
	return view('laboratories.radio');	//Лаборатория Компьютерные технологии и радиоэлектронника
})->name('laboratories.radio');

Route::get('/laboratories/modeling', function(){
	return view('laboratories.modeling');	//Лаборатория Компьютерные технологии и моделирование (Моделинг)
})->name('laboratories.modeling');

Route::get('/laboratories/fiziks', function(){
	return view('laboratories.fiziks');	//Физические основы высоких технологий
})->name('laboratories.fiziks');

Route::get('/laboratories/math', function(){
	return view('laboratories.math');	//Математика для будущих инженеров
})->name('laboratories.math');

Route::get('/laboratories/it', function(){
	return view('laboratories.it');	//Информатика
})->name('laboratories.it');