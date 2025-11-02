<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\PasswordResetController;

Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])
    ->middleware('throttle:5,1');

Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/me', fn(Request $request) => $request->user());

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });
});
