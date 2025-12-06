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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

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
Route::get('/market', [CoinPackageController::class, 'index'])->name('market');

// API Routes for AJAX
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/chapters/{id}/unlock', [ChapterController::class, 'unlock']);
});

// Comments & Reactions (AJAX)
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/toggle-approval', [CommentController::class, 'toggleApproval'])->name('comments.toggle-approval');
    
    Route::post('/reactions', [ReactionController::class, 'toggle'])->name('reactions.toggle');
});

// Public API
Route::get('/api/reactions', [ReactionController::class, 'index'])->name('api.reactions.index');

// Cart & Checkout
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/bank-transfer/{order}', [CheckoutController::class, 'bankTransfer'])->name('checkout.bank-transfer');
    Route::post('/checkout/bank-transfer/{order}', [CheckoutController::class, 'bankTransferSubmit'])->name('checkout.bank-transfer.submit');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failed', [CheckoutController::class, 'failed'])->name('checkout.failed');
});

// PayTR Callback (public)
Route::post('/payment/paytr/callback', [CheckoutController::class, 'paytrCallback'])->name('payment.paytr.callback');
