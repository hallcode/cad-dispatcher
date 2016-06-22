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
    Route::get('/me/incidents', 'HomeController@index')->name('me.incidents');
    Route::get('/me/networks', 'HomeController@networks')->name('me.networks');
    Route::get('/me/map', 'HomeController@map')->name('me.map');

    // Internal API
    Route::get('/iapi/incidents', 'HomeController@apiIncidents');

    // Resource Routes
    Route::resource('file', 'UploadController');
    Route::resource('n', 'NetworkController');

    // Incident routes
    Route::get('/n/{network}/i/{date}:{ref}', 'NetworkIncidentController@show')->name('incident.show');
    Route::get('/n/{network}/i/all', 'NetworkIncidentController@index')->name('incident.index');
    Route::get('/n/{network}/i/new', 'NetworkIncidentController@create')->name('incident.create');
    Route::get('/n/{network}/i/{date}:{ref}/edit', 'NetworkIncidentController@edit')->name('incident.edit');
    Route::post('/n/{network}/i/new', 'NetworkIncidentController@store')->name('incident.store');
    Route::post('/n/{network}/i/{date}:{ref}/edit', 'NetworkIncidentController@update')->name('incident.update');

    Route::get('/n/{network}/i/{date}:{ref}/update', 'NetworkIncidentController@update')->name('incident.addUpdate');
    Route::post('/n/{network}/i/{date}:{ref}/update', 'NetworkIncidentController@update')->name('incident.storeUpdate');

    // Network Routes
    Route::get('/n/{network}', 'NetworkController@show')->name('network.show');
});