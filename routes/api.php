<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;

/**
 * user authentication end points
 */
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

/**
 * tags end points
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('tags', TagController::class);
});

/**
 * Posts end points
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts/trashed', [PostController::class,'trached']);
    Route::get('posts/restore/{id}', [PostController::class,'restore']);
    Route::resource('posts', PostController::class);
});

/**
 * stats end point
 */
Route::get('/stats', [StatsController::class, 'stats']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
