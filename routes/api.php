<?php

use App\Http\Controllers\{AuthController, ProductController, UserController,};
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
Route::prefix("auth")->controller(AuthController::class)->group(function () {
    Route::get("/{social_network}/callback", "login")->name("auth.social_network");
    Route::post("/login", "login")->name("auth.login");
    Route::post("/forgot-password", "forgot")->name("auth.forgot");
    Route::post("/reset-password", "reset")->name("auth.reset");
    Route::post("/register", "register")->name("auth.register");
    Route::match([Request::METHOD_POST, Request::METHOD_GET], "/verify/{user}", "verify")
        ->name("auth.verify");
});

Route::prefix("admin")->middleware('auth:sanctum')->group(function () {
    Route::apiResource("user", UserController::class);
    Route::apiResource("product", ProductController::class);
});

Route::get("products", [ProductController::class, "index"]);
Route::get("product/{product}", [ProductController::class, "show"]);
