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
Route::group(['prefix'=> 'api'], function(){
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::group(['middleware'=>['auth:sanctum']], function () {
        Route::resource('columns', 'App\Http\Controllers\ColumnController');
        Route::resource('cards', 'App\Http\Controllers\CardController');
    });
});
