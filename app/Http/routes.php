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

    // Internal API
    Route::get('/iapi/incidents', 'HomeController@apiIncidents');

    // User Profile
    Route::get('/me', 'UserController@showCurrentUserProfile')->name('me');

    // Resource Routes
    Route::resource('file', 'UploadController');
    Route::resource('n', 'NetworkController');
    Route::resource('u', 'UserController');

    // ****** Incident routes ******
    // Show single incident
    Route::get('/n/{network}/i/{date}:{ref}', 'NetworkIncidentController@show')->name('incident.show');

    // Show all incidents in a network
    Route::get('/n/{network}/i', 'NetworkIncidentController@index')->name('incident.index');

    // Create an incident in a network
    Route::get('/n/{network}/i/new', 'NetworkIncidentController@create')->name('incident.create');

    // Edit an incident
    Route::get('/n/{network}/i/{date}:{ref}/edit', 'NetworkIncidentController@edit')->name('incident.edit');

    // Store a new incident in the databse
    Route::post('/n/{network}/i/new', 'NetworkIncidentController@store')->name('incident.store');

    // Store an edit in the databse
    Route::post('/n/{network}/i/{date}:{ref}/edit', 'NetworkIncidentController@update')->name('incident.update');

    // ****** Update Routes ******
    // Show the form to create an update
    Route::get('/n/{network}/i/{date}:{ref}/update', 'NetworkIncidentController@addUpdate')->name('incident.addUpdate');
    // Save an update to the database
    Route::post('/n/{network}/i/{date}:{ref}/update', 'NetworkIncidentController@storeUpdate')->name('incident.storeUpdate');

    // Network Routes
    Route::get('/n/{network}', 'NetworkController@show')->name('network.show');

    // Test Routes
    Route::get('/email', 'NetworkController@email');
});