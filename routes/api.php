<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use \App\Http\Middleware\CheckTokenMiddleware;


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

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

// Route::middleware([CheckTokenMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/order', OrderController::class);
// });

Route::post('/send-email', [EmailController::class, 'sendEmail']);
