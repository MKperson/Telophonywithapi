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

use App\Http\Controllers\InContactController;

Route::get('/', function () {
    return view('welcome');
});

// Route::view('/welcome', 'welcome');

//Route::view('/agentskills', 'agentskills');

Route::get('agentskills', 'InContactController@agentskills');

// Route::get('ajaxRequest', 'InContactController@ajaxRequest');

Route::post('getAgentSkills', 'InContactController@getAgentSkills');

//Route::post('setAgents', 'InContactController@setAgents');

Route::get('updateDB', 'InContactController@updateDB');

Route::post('setAgentsProf', 'InContactController@setAgentsProf');

Route::post('setskillprofs', 'InContactController@setSkillProfs');

// Route::get('forgetskillprof', 'InContactController@setSkillProfs');
Route::get('forgetskillprof', function () {
    session()->forget('skillidprof');
});

// Route::get('loopallagentskills', 'InContactController@loopallagentskills');

Route::match(['get', 'post'], 'addskill', 'InContactController@addSkill');

Route::match(['get', 'post'], 'delskill', 'InContactController@delSkill');

// Route::view('newskill','newskill');

Route::get('getSkills', 'InContactController@getSkills');

// Route::post('testpost', function () {
//     return view('testpost');
// });
// Route::view('/test', 'test');
