<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['prefix'=> 'user', 'middleware' => 'auth:sanctum'], function (){
    Route::get('/logout', [\App\Http\Controllers\UserController::class, 'logout']);

});

Route::group(['prefix'=> 'user'], function (){
    Route::post('/', [\App\Http\Controllers\UserController::class, 'store']);
    Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
});

Route::group(['prefix'=> 'admin'], function (){
    Route::post('/rates', [\App\Http\Controllers\RateController::class, 'store']);
    Route::get('/rates', [\App\Http\Controllers\RateController::class, 'index']);
    Route::post('/records', [\App\Http\Controllers\RecordController::class, 'store']);
    Route::get('/records', [\App\Http\Controllers\RecordController::class, 'index']);

    Route::get('/bills/{period}', [\App\Http\Controllers\BillController::class, 'index']);

    Route::group(['prefix' => 'residents'], function (){
        Route::get('/', [\App\Http\Controllers\ResidentController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\ResidentController::class, 'store']);
        Route::patch('/{resident}', [\App\Http\Controllers\ResidentController::class, 'update']);
    });
});


Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);
