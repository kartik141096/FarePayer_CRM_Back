<?php

use App\Http\Controllers\Api\queryController;
use App\Http\Controllers\Api\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckApiToken;

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


    Route::post('/register', [userController::class, 'register']); 
    Route::post('/login', [userController::class, 'login']);


    Route::middleware(['auth:api'])->group(function () {
        
        Route::post('/logout', [userController::class, 'logout']);
        Route::post('/getQueries', [queryController::class, 'getQueries']);
        Route::post('/addQuery', [queryController::class, 'addQuery']);
        
        // Route::post('/change-password', [userController::class, 'changePassword']);
        // Route::get('/user', [userController::class, 'getUser']);
        
    });