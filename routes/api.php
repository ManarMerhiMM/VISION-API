<?php
use Illuminate\Support\Facades\Route;


Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', fn (\Illuminate\Http\Request $r) => $r->user());
});
