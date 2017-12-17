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
    return response()->json([ 'hello' => 'world' ]);
});

Route::post('/users', 'UsersController@create');

Route::post('/auth', 'UsersController@login');

Route::group(['middleware' => 'checkToken'], function() {

	Route::delete('/auth', 'UsersController@logout');

	Route::post('/notes', 'NotesController@create');

	Route::get('/notes', 'NotesController@all');

	Route::get('/notes/{id}', 'NotesController@byId');

	Route::put('/notes/{id}', 'NotesController@update');

	Route::delete('/notes/{id}', 'NotesController@delete');

});
