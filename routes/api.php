<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\RecordController;

Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])
    ->middleware('throttle:5,1');

Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('throttle:5,1');

Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/testimonials', [UserController::class, 'testimonials']);

Route::get('/admins', [UserController::class, 'admins']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', fn(Request $request) => $request->user());

    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    Route::get('/users', [UserController::class, 'index']);

    Route::patch('/user', [UserController::class, 'update']);

    Route::post('/testimonial', [UserController::class, 'upsertTestimonial']);

    Route::post('/alert', [AlertController::class, 'store']);
    Route::post('/solve/{alert}', [AlertController::class, 'solve']);
    Route::get('/alerts/{user}', [AlertController::class, 'getUserAlerts']);
    
    Route::post('/record', [RecordController::class, 'store']);
    Route::get('/records/{user}', [RecordController::class, 'getUserRecords']);

    Route::post('/deactivate/{user}', [UserController::class, 'deactivate']);
    Route::post('/activate/{user}', [UserController::class, 'activate']);
});
