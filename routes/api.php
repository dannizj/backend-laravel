<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Middleware\JwtMiddleware;

// Rutas publicas de usuarios
Route::post("/user/register", [UserController::class, "register"]);
Route::post("/user/login", [UserController::class, "login"]);
Route::get("/user/profile/{id}", [UserController::class, "profile"]);
Route::get("/user/avatar/{file}", [UserController::class, "avatar"]);


// Rutas publicas de articulos
Route::get("/article/items/{page}", [ArticleController::class, "articles"]);
Route::get("/article/item/{id}", [ArticleController::class, "article"]);
Route::get("/article/user/{user_id}", [ArticleController::class, "articlesByUser"]);
Route::get("/article/search/{searchString}", [ArticleController::class, "search"]);
Route::get("/article/poster/{file}", [ArticleController::class, "poster"]);




Route::middleware([JwtMiddleware::class])->group(function() {
    
    // Rutas privadas de usuarios
    Route::put("/user/update", [UserController::class, "update"]);
    Route::post("/user/upload", [UserController::class, "upload"]);

    
    // Rutas privadas de articulos
    Route::post("/article/save", [ArticleController::class, "save"]);    
    Route::delete("/article/delete/{id}", [ArticleController::class, "delete"]);
    Route::put("/article/update/{id}", [ArticleController::class, "update"]);    
    Route::post("/article/upload/{id}", [ArticleController::class, "upload"]);    
    Route::post("/article/generate/{theme}", [ArticleController::class, "generate"]);  

});