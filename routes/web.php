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

/*DEVICES*/
Route::get('/getDevices/{user_id}',"DevicesController@getDevices");
Route::post('/saveDevice',"DevicesController@saveDevice");
Route::post('/deleteDevice',"DevicesController@deleteDevice");
Route::post('/newDevice',"DevicesController@newDevice");

/*PROGRAMS*/
Route::get('/programs/{device_id }',"ProgramsController@getPrograms");
Route::post('/saveProgram',"ProgramsController@saveProgram");
Route::post('/deleteProgram',"ProgramsController@deleteProgram");
Route::post('/newProgram',"ProgramsController@newProgram");

/*OUTPUTS*/
Route::get('/getOutputs/{program_id}',"OutputsController@getOutputs");
Route::post('/saveOutput',"OutputsController@saveOutput");
Route::post('/deleteOutput',"OutputsController@deleteOutput");
Route::post('/newOutput',"OutputsController@newOutput");

/*DAYS*/
Route::get('/getDays/{output_id}',"DaysController@getDays");
Route::post('/saveDay',"DaysController@saveDay");
Route::post('/deleteDay',"DaysController@deleteDay");
Route::post('/newDay',"DaysController@newDay");

/*SENSORS*/


/*TESTS*/
Route::get('/test', function () {
    return view('test');
});
Route::get('/debug', function () {
    return view('debug');
});