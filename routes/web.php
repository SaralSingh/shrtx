<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

Route::get('/{shortCode}', [RedirectController::class, 'handle'])
    ->where('shortCode', '[A-Za-z0-9]+');


Route::get('/', function () {
    return view('welcome');
});
