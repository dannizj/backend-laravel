<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;


Route::post("/user/register", [UserController::class, "register"]);
Route::post("/user/login", [UserController::class, "login"]);