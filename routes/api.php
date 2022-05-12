<?php

use App\Http\Controllers\AuthController;
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
Route::prefix("auth")->controller(AuthController::class)->group(function () {
    Route::get("/{social_network}/callback", "login")->name("login.social_network");
    Route::post("/login", "login")->name("login");
    Route::post("/forgot-password", "forgot")->name("forgot");
    Route::post("/reset-password", "reset")->name("reset");
    Route::post("/register", "register")->name("register");
});

Route::prefix("admin")->controller(\App\Http\Controllers\UserController::class)->group(function () {
    Route::get("/users", "index")->name("users.index");
});
