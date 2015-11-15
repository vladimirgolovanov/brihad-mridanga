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

//$user = Auth::user();

Route::get('/', function () {
	//print Auth::user()->name;
    return view('welcome');
});

Route::resource('bookgroups', 'BookGroupController');

Route::resource('books', 'BookController');

Route::resource('persons', 'PersonController');

Route::resource('bookprice', 'BookPriceController');

Route::get('bookprice/{id}/new', ['as'=>'newbookprice', 'uses'=>'BookPriceController@create']);

Route::get('report/{begin_date?}/{end_date?}', ['as'=>'report', 'uses'=>'ReportController@index']);
Route::post('report/{begin_date?}/{end_date?}', ['as'=>'report', 'uses'=>'ReportController@getselected']);

Route::get('persons/{personid}/operation/{operationid}', ['as'=>'persons.operation', 'uses'=>'PersonController@operation']);
Route::get('persons/{personid}/operation/{operationid}/edit/{bookid}', ['as'=>'persons.edit.operation', 'uses'=>'PersonController@edit_operation']);
Route::patch('persons/{personid}/operation/{operationid}/store/{bookid}', ['as'=>'persons.operation.store', 'uses'=>'PersonController@store_operation']);
Route::delete('persons/{personid}/operation/{operationid}/delete/{bookid}', ['as'=>'persons.operation.delete', 'uses'=>'PersonController@destroy_operation']);

Route::get('operation/{personid}/make', ['as'=>'operation.make', 'uses'=>'OperationController@make']);
Route::get('operation/{personid}/make_shop', ['as'=>'operation.make_shop', 'uses'=>'OperationController@make_shop']);
Route::get('operation/{personid}/make/{datetime}', ['as'=>'operation.make', 'uses'=>'OperationController@make']);
Route::get('operation/{personid}/laxmi', ['as'=>'operation.laxmi', 'uses'=>'OperationController@laxmi']);
Route::get('operation/{personid}/laxmi/{datetime}', ['as'=>'operation.laxmi', 'uses'=>'OperationController@laxmi']);
Route::get('operation/{personid}/remain', ['as'=>'operation.remain', 'uses'=>'OperationController@remain']);
Route::get('operation/{personid}/remain/{datetime}', ['as'=>'operation.remain', 'uses'=>'OperationController@remain']);
Route::get('operation/{personid}/return', ['as'=>'operation.return', 'uses'=>'OperationController@booksreturn']);
Route::get('operation/{personid}/return/{datetime}', ['as'=>'operation.return', 'uses'=>'OperationController@booksreturn']);
Route::post('operation', ['as'=>'operation.store', 'uses'=>'OperationController@store']);

// Authentication routes...
Route::get('auth/login', ['as'=>'auth.login', 'uses'=>'Auth\AuthController@getLogin']);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', ['as'=>'auth.logout', 'uses'=>'Auth\AuthController@getLogout']);
// Registration routes...
Route::get('auth/register', ['as'=>'auth.register', 'uses'=>'Auth\AuthController@getRegister']);
Route::post('auth/register', 'Auth\AuthController@postRegister');