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
    return view('welcome');
});

Route::get('/usuarios', 'UserController@index');

Route::get('usuarios/nuevo', 'UserController@create');

Route::get('usuarios/{id}', 'UserController@show');

Route::get('saludo/{name}/{nickname?}', 'WelcomeUserController
');