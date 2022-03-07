<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// User management
Route::get('/guest', 'GuestController@index');
Route::post('/guest/store', 'GuestController@store');
Route::put('/guest/update/{id}', 'GuestController@update');
Route::delete('/guest/delete/{id}', 'GuestController@delete');

Route::put('/guest/checkin', 'GuestController@checkin'); // user checked in, call this
Route::get('/guest/checkin', 'GuestController@getCheckedIn'); // get list of checked in users

Route::post('/guest/text', 'GuestController@show');