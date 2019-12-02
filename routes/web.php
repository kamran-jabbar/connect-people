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

Auth::routes();
/* @todo: there can create group for this task related stuff. */
Route::get('/dashboard', 'HomeController@index')->name('home');
Route::get('/create-meeting', 'MeetingController@createMeetingForm');
Route::post('/create-meeting', 'MeetingController@storeMeeting');
Route::get('/edit-meeting/{id}', 'MeetingController@editMeeting');
Route::get('/delete-meeting/{id}', 'MeetingController@deleteMeeting');
Route::get('/track-meeting/{id}', 'MeetingController@trackMeeting');
Route::get('/track-friends/{id}', 'MeetingController@trackFriends');
Route::get('/finish-task/{id}', 'MeetingController@finish');