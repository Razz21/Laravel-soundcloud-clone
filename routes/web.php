<?php

use Illuminate\Support\Facades\Route;

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

// todo admin
Route::group(['domain' => 'admin.localhost'], function () {
    Route::get('/', function () {
        return "This will respond to requests for 'admin.localhost/'";
    });
});

Auth::routes();
Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');

// Route::get('/home', 'HomeController@index')->name('home');
// Route::resource('profiles', 'ProfileController');
// Route::resource('subscriptions', 'SubscriptionController')->only(['store', 'destroy']);

// Route::get('/upload', 'TrackController@create')->name('files.create');
// Route::post('/upload', 'TrackController@store')->name('files.store');
// Route::get('/files/{file}', 'TrackController@show')->name('files.show');
