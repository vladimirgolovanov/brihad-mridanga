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

Route::resource('books', 'BookController');

Route::resource('persons', 'PersonController');

Route::resource('groups', 'GroupController');

Route::resource('bookprice', 'BookPriceController');

Route::get('bookprice/{id}/new', ['as'=>'newbookprice', 'uses'=>'BookPriceController@create']);

