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

Route::get('/also', function () { return view('welcome'); });

Route::view('/welcome', 'welcome');

//Route::view('/agentskills', 'agentskills');

Route::get('agentskills/', 'InContactController@agentskills');

Route::get('ajaxRequest', 'InContactController@ajaxRequest');

Route::post('getAgent', 'InContactController@getAgent');

//Route::post('setAgents', 'InContactController@setAgents');

Route::get('updateDB', 'InContactController@updateDB');


Route::get('home', function () {
    // Retrieve a piece of data from the session...
    $value = session('key');

    // Specifying a default value...
    $value = session('key', 'default');

    // Store a piece of data in the session...
    session(['key' => 'value']);
});