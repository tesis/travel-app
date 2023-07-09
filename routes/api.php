<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TravelController;
use App\Http\Controllers\Api\V1\TourController;
// use App\Http\Controllers\Api\V1\Admin\TravelController as AdminTravelController;
// use App\Http\Controllers\Api\V1\Admin\TourController as AdminTourController;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Auth\LoginController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('travels', [TravelController::class, 'index']);
// Route::get('travels/{travel:slug}/tours', [TourController::class, 'index']);
Route::get('travels/{travel}/tours', [TourController::class, 'index']);
/*Route::prefix('admin')->group(function () {
    // Route::post('travels', [Admin\TravelController::class, 'store']);
    Route::post('travels', [AdminTravelController::class, 'store']);
});*/
// Use sanctum middleware to protect the endpoint
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::post('travels', [Admin\TravelController::class, 'store']);
        Route::post('travels/{travel}/tours', [Admin\TourController::class, 'store']);
    });
    // update() should be accessed by both admin and editor roles
    Route::put('travels/{travel}', [Admin\TravelController::class, 'update']);
});

Route::post('login', LoginController::class);