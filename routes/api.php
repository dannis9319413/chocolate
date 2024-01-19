<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProductController;


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

Route::group(['middleware' => 'cors'], function () {
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/product', [ProductController::class, 'index']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::apiResource('/order', OrderController::class)->except('store');
        Route::post('/send-email', [EmailController::class, 'sendEmail']);
    });
});
