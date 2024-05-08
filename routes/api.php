<?php

use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('auth/{driver}/callback', [UserController::class, 'Authcallback']);
Route::post('/otp/verification', [UserController::class, 'otp_verification']);
Route::post('/forgot/password', [UserController::class, 'forgotPassword']);
Route::post('/forgot/change/password', [UserController::class, 'forgotChangePassword']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('change/password', [UserController::class, 'changePassword']);
});