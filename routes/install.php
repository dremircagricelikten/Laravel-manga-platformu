<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;

// Installation routes
Route::prefix('install')->middleware('web')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('install.index');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('install.requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/test-database', [InstallController::class, 'testDatabase'])->name('install.test-database');
    Route::post('/save-database', [InstallController::class, 'saveDatabase'])->name('install.save-database');
    Route::post('/migrate', [InstallController::class, 'migrate'])->name('install.migrate');
    Route::get('/admin', [InstallController::class, 'admin'])->name('install.admin');
    Route::post('/create-admin', [InstallController::class, 'createAdmin'])->name('install.create-admin');
    Route::get('/finalize', [InstallController::class, 'finalize'])->name('install.finalize');
});
