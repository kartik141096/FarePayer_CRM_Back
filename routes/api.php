<?php

use App\Http\Controllers\Api\destinationController;
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

        // DESTINATION APIs-----------------------------------------------------------------------------------
        Route::get('/searchDestination', [destinationController::class, 'searchDestination']);
        Route::post('/findDestinationName', [destinationController::class, 'findDestinationName']);
        
        // USER APIs-----------------------------------------------------------------------------------
        Route::post('/logout', [userController::class, 'logout']);
        Route::post('/isLoggedin', [userController::class, 'isLoggedin']);
        Route::get('/getUsers', [UserController::class, 'getUsers']);
        Route::get('/getUserDetails/{id}', [UserController::class, 'getUserDetails']);
        Route::get('/getAllRoles', [UserController::class, 'getAllRoles']);
        Route::get('/deleteUser/{id}', [UserController::class, 'deleteUser']);
        Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);
        Route::post('/changePassword/{id}', [userController::class, 'changePassword']);
        Route::post('/getSalesUsers', [userController::class, 'getSalesUsers']);
        
        // QUERY APIs-----------------------------------------------------------------------------------
        Route::post('/getQueries', [queryController::class, 'getQueries']);
        Route::post('/getQueryDestinations', [queryController::class, 'getQueryDestinations']);
        Route::post('/addQuery', [queryController::class, 'addQuery']);
        // Route::get('/search-cities', [queryController::class, 'searchCities']);
        
        // ITINERARY APIs-----------------------------------------------------------------------------------
        Route::post('/addItinerary', [itineraryController::class, 'addItinerary']);
        Route::post('/getItineraryByID', [itineraryController::class, 'getItineraryByID']);
        Route::post('/updateItinerary', [itineraryController::class, 'updateItinerary']);
        Route::post('/getItineraries', [itineraryController::class, 'getItineraries']);
        Route::post('/getItineraryDestinations', [itineraryController::class, 'getItineraryDestinations']);
        Route::post('/getItineraryDetails', [itineraryController::class, 'getItineraryDetails']);
        Route::post('/getHotelsByDestinations', [itineraryController::class, 'getHotelsByDestinations']);
        Route::post('/getActivitiesByDestinations', [itineraryController::class, 'getActivitiesByDestinations']);
        Route::post('/updateItineraryMaster', [itineraryController::class, 'updateItineraryMaster']);
        Route::post('/filterHotelPrice', [itineraryController::class, 'filterHotelPrice']);
        Route::post('/AddHotelToItinerary', [itineraryController::class, 'AddHotelToItinerary']);
        Route::post('/getItineraryItemByIdAndTable', [itineraryController::class, 'getItineraryItemByIdAndTable']);
        Route::post('/updateItineraryHotel', [itineraryController::class, 'updateItineraryHotel']);
        Route::post('/deleteItinerarySlave', [itineraryController::class, 'deleteItinerarySlave']);
        Route::post('/addActivityToItinerary', [itineraryController::class, 'addActivityToItinerary']);
        
        // SETTINGS APIs-----------------------------------------------------------------------------------
        Route::post('/getRoomTypes', [settingsController::class, 'getRoomTypes']);
        Route::post('/deleteRoomType', [settingsController::class, 'deleteRoomType']);
        Route::post('/changeRoomTypeStatus', [settingsController::class, 'changeRoomTypeStatus']);
        Route::post('/addRoomType', [settingsController::class, 'addRoomType']);

        Route::post('/getMealPlan', [settingsController::class, 'getMealPlan']);
        Route::post('/changeMealPlanStatus', [settingsController::class, 'changeMealPlanStatus']);
        Route::post('/addMealPlan', [settingsController::class, 'addMealPlan']);
        Route::post('/deleteMealPlan', [settingsController::class, 'deleteMealPlan']);

        Route::post('/getAllHotels', [settingsController::class, 'getAllHotels']);
        Route::post('/addhotel', [settingsController::class, 'addhotel']);
        Route::post('/changeHotelStatus', [settingsController::class, 'changeHotelStatus']);
        Route::post('/updateHotel', [settingsController::class, 'updateHotel']);
        Route::get('/deleteHotel/{id}', [settingsController::class, 'deleteHotel']);
        Route::post('/addHotelPrice', [settingsController::class, 'addHotelPrice']);
        Route::post('/getHotelPriceList', [settingsController::class, 'getHotelPriceList']);
        Route::post('/deleteHotelPrice', [settingsController::class, 'deleteHotelPrice']);

        Route::post('/getAllActivities', [settingsController::class, 'getAllActivities']);
        Route::post('/addActivity', [settingsController::class, 'addActivity']);
        Route::post('/changeActivityStatus', [settingsController::class, 'changeActivityStatus']);
        Route::get('/deleteActivity/{id}', [settingsController::class, 'deleteActivity']);
        Route::post('/updateActivity', [settingsController::class, 'updateActivity']);
        Route::post('/addActivityPrice', [settingsController::class, 'addActivityPrice']);
        Route::post('/getActivityPriceList', [settingsController::class, 'getActivityPriceList']);
        Route::post('/deleteActivityPrice', [settingsController::class, 'deleteActivityPrice']);

        Route::post('/addTransfer', [settingsController::class, 'addTransfer']);
        Route::post('/getAllTransfers', [settingsController::class, 'getAllTransfers']);
        Route::post('/changeTransferStatus', [settingsController::class, 'changeTransferStatus']);
        Route::post('/updateTransfer', [settingsController::class, 'updateTransfer']);
        Route::get('/deleteTransfer/{id}', [settingsController::class, 'deleteTransfer']);
        Route::post('/addTransferPrice', [settingsController::class, 'addTransferPrice']);
        Route::post('/getTransferPriceList', [settingsController::class, 'getTransferPriceList']);
        Route::post('/deleteTransferPrice', [settingsController::class, 'deleteTransferPrice']);
        Route::post('/getAllSuppliers', [settingsController::class, 'getAllSuppliers']);
        Route::post('/addSupplier', [settingsController::class, 'addSupplier']);
        Route::get('/deleteSupplier/{id}', [settingsController::class, 'deleteSupplier']);

    });