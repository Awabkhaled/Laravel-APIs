<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Tag;
use App\Http\Controllers\TagController;

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('tags', TagController::class);
});

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
