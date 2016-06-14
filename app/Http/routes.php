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

Route::singularResourceParameters();

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

    // Resource Routes
    Route::resource('upload', 'UploadController');
    Route::resource('network', 'NetworkController');
    Route::resource('network.incident', 'NetworkIncidentController');
    Route::resource('network.incident.update', 'NetworkIncidentUpdateController');
    Route::resource('network.grade', 'NetworkGradeController');
    Route::resource('network.type', 'NetworkTypeController');
    Route::resource('network.user', 'NetworkUserController');

});