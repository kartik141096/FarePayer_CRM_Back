<?php

use App\Http\Controllers\Api\itineraryController;
use App\Http\Controllers\Api\queryController;
use App\Http\Controllers\Api\settingsController;
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


    Route::middleware(['auth:api', 'refresh.token'])->group(function () {
        
        Route::post('/logout', [userController::class, 'logout']);
        Route::post('/isLoggedin', [userController::class, 'isLoggedin']);
        Route::get('/getUsers', [UserController::class, 'getUsers']);
        Route::get('/getUserDetails/{id}', [UserController::class, 'getUserDetails']);
        Route::get('/getAllRoles', [UserController::class, 'getAllRoles']);
        Route::get('/deleteUser/{id}', [UserController::class, 'deleteUser']);
        Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);
        Route::post('/changePassword/{id}', [userController::class, 'changePassword']);
        Route::post('/getSalesUsers', [userController::class, 'getSalesUsers']);
        
        Route::post('/getQueries', [queryController::class, 'getQueries']);
        Route::post('/getQueryDestinations', [queryController::class, 'getQueryDestinations']);
        Route::post('/addQuery', [queryController::class, 'addQuery']);
        Route::get('/search-cities', [queryController::class, 'searchCities']);
        
        Route::post('/addItinerary', [itineraryController::class, 'addItinerary']);
        Route::post('/getItineraries', [itineraryController::class, 'getItineraries']);
        Route::post('/getItineraryDestinations', [itineraryController::class, 'getItineraryDestinations']);
        Route::post('/getItineraryDetails', [itineraryController::class, 'getItineraryDetails']);
        
        Route::post('/getRoomTypes', [settingsController::class, 'getRoomTypes']);
        Route::post('/deleteRoomType', [settingsController::class, 'deleteRoomType']);
        Route::post('/changeRoomTypeStatus', [settingsController::class, 'changeRoomTypeStatus']);
        Route::post('/addRoomType', [settingsController::class, 'addRoomType']);
    });