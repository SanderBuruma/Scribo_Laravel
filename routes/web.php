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


Route::group(['middleware' => ['web']], function(){

	Route::get('/verification/verify', "HomeController@index")->name('verification.verify');

	Route::get('/ajax/text', "AjaxController@text");
	Route::post('/ajax/chapter', "AjaxController@chapter");
	Route::post('/ajax/verse', "AjaxController@verse");
	Route::post('/ajax/saints', "AjaxController@returnSaints");
	
	Route::get('/ajax/leaderboard', "AjaxController@leaderboard");
	
	Route::get('/', 'HomeController@index')->name('home');
	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('user/{slug}', 'UserController@show')->name('user.show');
});

Route::group(['middleware' => ['web','auth','role:!4']], function(){
	Route::group(['middleware' => ['role:3']], function(){
		Route::resource('/admin', 'AdminInterfaceController');
		Route::get('/adminajax', 'AdminInterfaceController@indexAjax')->name('admin.index.ajax');
		Route::resource('/text', 'TextController');
	});
	Route::resource('/user', 'UserController')->except('show');
	Route::resource('/race', 'RaceController');
});

Auth::routes();

