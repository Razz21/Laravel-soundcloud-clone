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

    Route::put('profile', 'ProfileController@update');
    Route::apiResource('/users', 'UserController');
    Route::apiResource('/tags', 'TagController');
    Route::apiResource('/genres', 'GenreController');

    // subscriptions
    Route::apiResource('subscriptions', 'User\SubscriptionController')->only(['index', 'store', 'destroy']);
    Route::get('subscribers', 'User\SubscriptionController@subscribers');

    // tracks
    Route::apiResource('users.tracks', 'User\TrackController')->only(['index', 'show']);
    Route::apiResource('/tracks', 'TrackController');
    Route::apiResource('tracks.comments', 'Track\CommentController');
    Route::apiResource('tracks.likes', 'Track\LikeController')->only(['index', 'store']);

    // Route::apiResource('/likes', 'LikeController')->only(['store', 'destroy']);
    // Route::post('/tracks/{track}/comments/{comment}', 'Track\CommentController@reply');

});
