<?php

use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.licenses.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('licenses', LicenseController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::patch('licenses/{license}/suspend', [LicenseController::class, 'suspend'])->name('licenses.suspend');
        Route::patch('licenses/{license}/activate', [LicenseController::class, 'activate'])->name('licenses.activate');
    });
});

require __DIR__.'/auth.php';
