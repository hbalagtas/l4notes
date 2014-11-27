<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('about', ['as' => 'about', 'uses' => 'HomeController@about']);

Route::get('login', ['as'=>'login', 'uses' => 'HomeController@loginWithGoogle']);
Route::get('logout', ['as'=>'logout', function()
{
	Auth::logout();
	return Redirect::route('homepage');
}]);

Route::get('/', ['as'=>'homepage', 'uses' => 'NoteController@index']);

Route::group(['before' => 'auth'], function(){
	Route::resource('note', 'NoteController', ['except' => 'index']);

	Route::get('/import-tags', ['as'=>'import.tags', 'uses' => 'HomeController@ImportTags']);
	Route::get('/import-notes', ['as'=>'import.notes', 'uses' => 'HomeController@ImportNotes']);
});