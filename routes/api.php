<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);

Route::apiResource('genres', GenreController::class);

Route::apiResource('books', BookController::class);
