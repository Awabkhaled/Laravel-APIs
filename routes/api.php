<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Tag;

Route::get('/tags',function(){
    return Tag::all();
});

Route::post('/tags', function (Request $request) {
    return Tag::create(['name' => $request->input('name')]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
