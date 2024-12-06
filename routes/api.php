<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/tags',function(){
    return 'tags';
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
