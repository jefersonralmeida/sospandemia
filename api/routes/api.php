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

Route::middleware('auth:api')->group(function() {

    // auth routes
    Route::post('logout', 'AuthController@logout');

    // profile routes
    Route::get('profile', 'ProfileController@info');

    Route::get('entities/{entity}/demands', 'DemandsController@index');

});
