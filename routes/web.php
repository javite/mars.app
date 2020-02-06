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
    return view('init');
});
Route::get('/main', function () {
    // $vac = compact("id");
    return view('main');
});
Route::get('/stats', function () {
    return view('stats');
});
Route::get('/program', function () {
    return view('program');
});

Route::get('/programs/{data}',"ProgramsController@getPrograms");
Route::post('/saveProgram',"ProgramsController@saveProgram");
Route::post('/deleteProgram',"ProgramsController@deleteProgram");
Route::post('/newProgram',"ProgramsController@newProgram");

Route::get('/test', function () {
    return view('test');
});
Route::get('/debug', function () {
    return view('debug');
});