<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/getDevices/{user_id}',"DevicesController@getDevices");
Route::get('/getDevice/{device_id}',"DevicesController@getDevice");
Route::get('/getPrograms/{device_id}',"ProgramsController@getPrograms");
Route::get('/getOutputs/{program_id}',"OutputsController@getOutputs");
Route::get('/getOutputsBoard/{program_id}',"OutputsController@getOutputsBoard");
Route::get('/getDays/{output_id}',"DaysController@getDays");

Route::post('/newMeasurement',"MeasurementsController@newMeasurement");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
