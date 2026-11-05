<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;


Route::post("/user/register", [UserController::class, "register"]);
Route::post("/user/login", [UserController::class, "login"]);
Route::get("/user/profile/{id}", [UserController::class, "profile"]);
Route::get("/user/avatar/{file}", [UserController::class, "avatar"]);

Route::middleware([JwtMiddleware::class])->group(function() {
    
    Route::put("/user/update", [UserController::class, "update"]);
    Route::post("/user/upload", [UserController::class, "upload"]);

});