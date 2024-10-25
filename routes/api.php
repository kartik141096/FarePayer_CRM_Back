<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Test;
use App\Http\Controllers\AuthController; 
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware('auth:api')->group(function () {
    
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });    
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
// Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
// Route::post('getUser', [AuthController::class, 'getUserDetails']);

// Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
