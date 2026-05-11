<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SlowLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class,'index']);

Route::get('/slow-logs', [SlowLogController::class, 'index']);