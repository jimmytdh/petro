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
Route::get('/login','MainController@login');
Route::post('/login','MainController@validateLogin');
Route::get('logout',function(){
    \Illuminate\Support\Facades\Session::flush();
    return redirect('/login');
});

Route::get('/','HomeController@index');

Route::get('/home/radar/label','HomeController@radarLabel');
Route::get('/home/radar/data','HomeController@radarData');
Route::get('/home/chart/data','HomeController@chartData');

Route::get('/participants','ParticipantCtrl@index');
Route::post('/participants/save','ParticipantCtrl@save');
Route::get('/participants/delete/{id}','ParticipantCtrl@delete');
Route::get('/participants/edit/{id}','ParticipantCtrl@edit');
Route::post('/participants/update/{id}','ParticipantCtrl@update');
Route::post('/participants/search','ParticipantCtrl@search');

Route::get('/division','DivisionCtrl@index');
Route::post('/division/save','DivisionCtrl@save');
Route::get('/division/delete/{id}','DivisionCtrl@delete');
Route::get('/division/edit/{id}','DivisionCtrl@edit');
Route::post('/division/update/{id}','DivisionCtrl@update');
Route::post('/division/search','DivisionCtrl@search');

Route::get('/trainings','TrainingCtrl@index');
Route::post('/trainings/save','TrainingCtrl@save');
Route::get('/trainings/delete/{id}','TrainingCtrl@delete');
Route::get('/trainings/edit/{id}','TrainingCtrl@edit');
Route::post('/trainings/update/{id}','TrainingCtrl@update');
Route::post('/trainings/search','TrainingCtrl@search');

Route::get('/trainings/list/{id}','TrainingCtrl@list');
Route::post('/trainings/add/{id}','TrainingCtrl@add');
Route::get('/trainings/participants/delete/{training_id}/{participant_id}','TrainingCtrl@deleteParticipant');

Route::get('/monitoring','MonitoringCtrl@index');
Route::post('/monitoring/{column}/{id}','MonitoringCtrl@save');
Route::post('/monitoring/search','MonitoringCtrl@search');

Route::get('/load/info/{id}','MonitoringCtrl@info');
Route::get('/loading',function(){
    return view('page.loading');
});