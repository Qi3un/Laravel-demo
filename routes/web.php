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

Route::post('/users', 'Auth\RegisterController@create');

Route::post('/auth', 'Auth\LoginController@login');

Route::group(['middleware' => 'checkToken'], function() {

	Route::delete('/auth', 'Auth\LogoutController@logout');

	Route::post('/notes', 'Note\CreateController@create');

	Route::get('/notes', 'Note\QueryController@all');

	Route::get('/notes/{id}', 'Note\QueryController@byId');

	Route::put('/notes/{id}', 'Note\UpdateController@update');

	Route::delete('/notes/{id}', 'Note\DeleteController@delete');

});
