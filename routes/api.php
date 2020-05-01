<?php

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

Route::namespace ('Api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', 'UserController@me');
    });

    // subscriptions
    Route::apiResource('subscriptions', 'User\SubscriptionController')->only(['index', 'store', 'destroy']);
    Route::get('subscribers', 'User\SubscriptionController@subscribers');
    Route::get('/users/{user}/subscribers', 'UserController@subscribers');

    Route::put('profile', 'ProfileController@update');
    Route::apiResource('/users', 'UserController')->except(['index']);
    Route::apiResource('/tags', 'TagController');
    Route::apiResource('/genres', 'GenreController')->only(['index', 'show']);

    // listening history
    Route::delete('history', 'HistoryController@destroy')->name('history.destroy');
    Route::apiResource('history', 'HistoryController')->only(['index', 'store']);
    Route::apiResource('users.history', 'User\HistoryController')->only(['index']);
    Route::apiResource('queue', 'PlayerQueueController')->only(['index', 'store']);

    // tracks
    Route::apiResource('users.tracks', 'User\TrackController')->only(['index', 'show']);
    Route::apiResource('/tracks', 'TrackController');
    Route::apiResource('tracks.comments', 'Track\CommentController');
    Route::apiResource('tracks.likes', 'Track\LikeController')->only(['index', 'store']);

});
