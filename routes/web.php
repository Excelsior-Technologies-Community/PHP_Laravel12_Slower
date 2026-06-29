<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SlowLogController;

Route::get('/', function () {
    return redirect()->route('products');
});

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/slow-logs', [SlowLogController::class, 'index'])->name('slow-logs');
Route::get('/slow-logs/{id}', [SlowLogController::class, 'show'])->name('slow-logs.show');
Route::delete('/slow-logs/{id}', [SlowLogController::class, 'destroy'])->name('slow-logs.destroy');
Route::post('/slow-logs/clear', [SlowLogController::class, 'clearAll'])->name('slow-logs.clear');
Route::post('/slow-logs/analyze/{id}', [SlowLogController::class, 'analyze'])->name('slow-logs.analyze');
Route::get('/slow-logs/explain/{id}', [SlowLogController::class, 'explain'])->name('slow-logs.explain');