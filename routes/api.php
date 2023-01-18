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
// prefix('api')->

Route::group(['prefix'=> 'v1'], function(){
   
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    // Route::get('export', 'App\Http\Controllers\CardController@export');
    Route::get('filter', 'App\Http\Controllers\CardController@filter');
    Route::group(['middleware'=>['auth:sanctum']], function () {
        Route::resource('columns', 'App\Http\Controllers\ColumnController');
        Route::post('cards/{id}', 'App\Http\Controllers\CardController@store');
        Route::put('cards/{id}', 'App\Http\Controllers\CardController@update');
        Route::delete('cards/{id}', 'App\Http\Controllers\CardController@destroy');
        Route::get('cards/{id}', 'App\Http\Controllers\CardController@show');
        // Route::resource('cards', 'App\Http\Controllers\CardController');
    });
});
