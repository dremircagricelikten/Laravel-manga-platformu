<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\CoinPackageController;
use App\Http\Controllers\InstallController;

// Installation Routes
Route::prefix('install')->middleware('web')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('install.welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('install.requirements');
    Route::get('/database', [InstallController::class, 'database'])->name('install.database');
    Route::post('/database/test', [InstallController::class, 'testDatabase'])->name('install.database.test');
    Route::post('/database/save', [InstallController::class, 'saveDatabase'])->name('install.database.save');
    Route::post('/migrate', [InstallController::class, 'migrate'])->name('install.migrate');
    Route::get('/admin', [InstallController::class, 'admin'])->name('install.admin');
    Route::post('/admin', [InstallController::class, 'createAdmin'])->name('install.admin.create');
    Route::get('/finalize', [InstallController::class, 'finalize'])->name('install.finalize');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Series & Chapters
Route::get('/series/{slug}', [SeriesController::class, 'show'])->name('series.show');
Route::post('/series/{slug}/nsfw-accept', [SeriesController::class, 'acceptNsfw'])->name('series.nsfw-accept');
Route::get('/chapter/{slug}', [ChapterController::class, 'read'])->name('chapter.read');

// Browse & Search
Route::get('/browse', [BrowseController::class, 'index'])->name('browse');
Route::get('/latest', [BrowseController::class, 'index'])->name('latest');
Route::get('/popular', [BrowseController::class, 'index'])->name('popular');

// User Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');

// Coin Packages
Route::get('/coin-packages', [CoinPackageController::class, 'index'])->name('coin-packages');

// API Routes for AJAX
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/chapters/{id}/unlock', [ChapterController::class, 'unlock']);
});
