<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'api_key' => Auth::user()->api_key,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/guide', function () {
    return Inertia::render('Guide/Guide', []);
})->middleware(['auth'])->name('guide');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// routes/web.php


Route::middleware('auth')->group(function () {
    Route::post('/generate-article', [ArticleController::class, 'generate']);
    Route::post('/profile/update-api-key', [ProfileController::class, 'updateApiKey'])
        ->name('profile.update-api-key');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
});
