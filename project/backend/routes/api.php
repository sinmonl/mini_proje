<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Log;



Route::get('/test', function () {
    return response()->json([
        'message' => 'API calisiyor!',
        'timestamp' => now()
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('contact')->group(function () {
    Route::get('/cities', [ContactController::class, 'getCities']);
    Route::get('/districts/{cityName}', [ContactController::class, 'getDistricts']);
    Route::post('/', [ContactController::class, 'store']);
});

Route::get('/products', [ProductController::class, 'index']);