<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;


Route::post("/user/register", [UserController::class, "register"]);
Route::post("/user/login", [UserController::class, "login"]);

Route::middleware([JwtMiddleware::class])->group(function() {
    
    Route::put("/user/update", [UserController::class, "update"]);

});