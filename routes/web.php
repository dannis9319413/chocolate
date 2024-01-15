<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('http://fetchtwshop.com');
});

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

// Route::group(['middleware' => 'auth:api'], function () {
Route::post('/logout', [AuthController::class, 'logout']);
Route::resource('/order', OrderController::class);
// });

Route::post('/send-email', [EmailController::class, 'sendEmail']);

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
