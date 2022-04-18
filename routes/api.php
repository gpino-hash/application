<?php

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

Route::controller(\App\Http\Controllers\AuthController::class)->group(function () {
    Route::get("/auth/{social_network}/callback", "login");
    Route::post("/auth/login", "login");
});

Route::post("/auth/forgot-password", [\App\Http\Controllers\PasswordController::class, "forgot"]);
Route::post("/auth/register", [\App\Http\Controllers\RegisterController::class, "register"]);
