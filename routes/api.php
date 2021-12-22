<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\Auth;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix("v1/orders")->middleware([Auth::class])->group(function(){
    Route::post('', [OrderController::class, 'createOrder']);
    Route::post('search', [OrderController::class, 'getOrders']);
    Route::get('{orderId}', [OrderController::class, 'getOrderById']);
});

Route::prefix("v1/services")->middleware([Auth::class])->group(function(){
    Route::get('', [ServiceController::class, 'getServices']);
});

Route::prefix("v1/cars")->middleware([Auth::class])->group(function(){
    Route::post('list', [CarController::class, 'getCars']);
    Route::post('search', [CarController::class, 'getCarsWithSearch']);
});

Route::prefix("v1/account")->middleware([Auth::class])->group(function(){
    Route::get('balance', [BalanceController::class, 'getCurrentBalance']);
    Route::post('balance', [BalanceController::class, 'createBalanceTransaction']);
});
