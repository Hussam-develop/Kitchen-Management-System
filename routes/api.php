<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KitchenController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubLocationController;
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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('updateUserRole', [AuthController::class, 'updateRole']);
    Route::post('kitchens/updateName', [KitchenController::class, 'updateName']);
    Route::apiResource('locations', LocationController::class);
    Route::apiResource('sub-locations', SubLocationController::class);
    Route::post('products/{id}/withdraw', [OrderController::class, 'withdraw']);
    Route::post('orders/{id}/confirm-withdraw', [OrderController::class, 'confirmWithdraw']);
    Route::post('orders/{id}/return', [OrderController::class, 'returnProduct']);
    Route::post('orders/{id}/confirm-return', [OrderController::class, 'confirmReturn']);
    Route::apiResource('products', ProductController::class);

});

/*
    Route::middleware(['auth:api', 'role:user'])->group(function () {
        Route::get('user', function () {
            return response()->json(['message' => 'Welcome User']);
        });
    });

 */
