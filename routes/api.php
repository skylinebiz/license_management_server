<?php

use App\Http\Controllers\LicenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('license/{host}', [LicenseController::class, 'getByHost']);
Route::patch('license/{host}/usage', [LicenseController::class, 'reportUsage']);
