<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShortenController;

Route::post('/shorten', [ShortenController::class, 'store'])
    ->middleware('throttle:shorten');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

