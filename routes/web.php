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
});//->middleware('auth');
Route::get('/home',function () {
    return view('home');
});//->middleware('auth');
Route::get('/stats', function () {
    return view('stats');
});//->middleware('auth');
Route::get('/program', function () {
    return view('program');
});//->middleware('auth');
Route::get('/config', function () {
    return view('config');
});//->middleware('auth');

/*DEVICES*/
Route::get('/getDevices',"DevicesController@getDevices");
Route::post('/saveDevice',"DevicesController@saveDevice");
Route::post('/deleteDevice',"DevicesController@deleteDevice");
Route::post('/newDevice',"DevicesController@newDevice");

/*PROGRAMS*/
Route::get('/getPrograms/{device_id}',"ProgramsController@getPrograms");
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
Route::get('/getDaysNames',"DaysController@getDaysNames");

/*MEASUREMENTS*/
Route::get('/getMeasurement',"MeasurementsController@getMeasurement");
Route::get('/getMeasurements',"MeasurementsController@getMeasurements");
Route::get('/getLastMeasurement/{device_id}',"MeasurementsController@getLastMeasurement");
Route::get('/getSensorMeasurements',"MeasurementsController@getSensorMeasurements");
Route::post('/saveMeasurements',"MeasurementsController@saveMeasurements");
Route::post('/deleteMeasurements',"MeasurementsController@deleteMeasurements");
Route::post('/newMeasurement',"MeasurementsController@newMeasurement");

/*SENSORS*/


/*TESTS*/
Route::get('/test', function () {
    return view('test');
});
Route::get('/service/migrate',function(){
    Artisan::call('migrate', [
        '--force' => true,
    ]);
});
Route::get('/programList',"DevicesController@programList");
Route::get('/outputList',"ProgramsController@outputList");
Route::get('/debug', function () {
    return view('debug');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
