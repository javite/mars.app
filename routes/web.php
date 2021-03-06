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

// Route::get('/', function () {
//     return view('home');
// })->middleware('auth');//->middleware('auth');
Route::get('/', 'HomeController@index');//->name('home')->middleware('auth')
Route::get('/home', 'HomeController@index');
Route::get('/wai', function () {
    $response = array(
        "conn" => "web"
    );
    return json_encode($response);
});

// Route::get('/',function () {
//     return view('home');
// })->middleware('auth');//->middleware('auth');
// Route::get('/stats', function () {
//     return view('stats');
// });//->middleware('auth');
// Route::get('/program', function () {
//     return view('program');
// });//->middleware('auth');
// Route::get('/config', function () {
//     return view('config');
// });//->middleware('auth');

/*DEVICES*/
Route::get('/getDevices/{user_id}',"DevicesController@getDevices");
// Route::post('/saveDevice',"DevicesController@saveDevice");
// Route::post('/deleteDevice',"DevicesController@deleteDevice");
// Route::post('/newDevice',"DevicesController@newDevice");

/*PROGRAMS*/
// Route::get('/getPrograms/{device_id}',"ProgramsController@getPrograms");
// Route::post('/saveProgram',"ProgramsController@saveProgram");
// Route::post('/deleteProgram',"ProgramsController@deleteProgram");
// Route::post('/newProgram',"ProgramsController@newProgram");
// Route::get('/loadProgram/{device_id}/{program_id}',"ProgramsController@loadProgram");

/*OUTPUTS*/
// Route::get('/getOutputs/{program_id}',"OutputsController@getOutputs");
// Route::post('/saveOutput',"OutputsController@saveOutput");
// Route::post('/deleteOutput',"OutputsController@deleteOutput");
// Route::post('/newOutput',"OutputsController@newOutput");

/*DAYS*/
// Route::get('/getDays/{output_id}',"DaysController@getDays");
// Route::post('/saveDay',"DaysController@saveDay");
// Route::post('/deleteDay',"DaysController@deleteDay");
// Route::post('/newDay',"DaysController@newDay");
// Route::get('/getDaysNames',"DaysController@getDaysNames");

/*MEASUREMENTS*/
// Route::get('/getMeasurement',"MeasurementsController@getMeasurement");
// Route::get('/getMeasurements',"MeasurementsController@getMeasurements");
// Route::get('/getLastMeasurement/{device_id}',"MeasurementsController@getLastMeasurement");
// Route::get('/getSensorMeasurements',"MeasurementsController@getSensorMeasurements");
// Route::post('/saveMeasurements',"MeasurementsController@saveMeasurements");
// Route::post('/deleteMeasurements',"MeasurementsController@deleteMeasurements");
// Route::post('/newMeasurement',"MeasurementsController@newMeasurement");

/*PLANTS*/
// Route::get('/getPlants',"PlantsController@getPlants");
// Route::post('/savePlant',"PlantsController@savePlant");
// Route::post('/deletePlant',"PlantsController@deletePlant");
// Route::post('/newPlant',"PlantsController@newPlant");

/*EVENTS*/
// Route::get('/getEvents/{plant_id}',"EventsController@getEvents");
// Route::post('/saveEvent',"EventsController@saveEvent");
// Route::post('/deleteEvent',"EventsController@deleteEvent");
// Route::post('/newEvent',"EventsController@newEvent");

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
// Route::get('/programList',"DevicesController@programList");
// Route::get('/outputList',"ProgramsController@outputList");
Route::get('/debug', function () {
    return view('debug');
});

// Route::get('/updateProgram/{day_id}',"DaysController@updateProgram");
// Route::get('/updateProgramOutput/{output_id}',"OutputsController@updateProgram");

Auth::routes(['register' => false]); 


//         // Authentication Routes...
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

//         // Registration Routes...

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController_1@register');

// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
// Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
// Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
// Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

