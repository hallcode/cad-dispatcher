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

Route::auth();

/*
 * Routes Requiring HTTP Form Authentication
 */
Route::group(['middleware' => 'auth'], function () {

    // Home
    Route::get('/home', 'HomeController@index');
    Route::get('/incidents', 'HomeController@index');
    Route::get('/networks', 'HomeController@networks');

    // Resource Routes
    Route::resource('upload', 'UploadController');
    Route::resource('network', 'NetworkController');

});