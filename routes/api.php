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

Route::post("/auth/login", [\App\Http\Controllers\AuthController::class, "login"]);
Route::get("/auth/{social_network}/callback", [\App\Http\Controllers\AuthController::class, "login"]);
