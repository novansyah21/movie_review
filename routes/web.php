<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LanguageController;

// Public Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('locale/{locale}', [LanguageController::class, 'changeLocale'])->name('locale.change');

// Protect these routes with authentication
Route::middleware(['auth'])->group(function() {
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');
    Route::post('/movies/{imdbID}/favorite', [MovieController::class, 'addToFavorites']);
});

Route::get('/movies/details/{imdbID}', [MovieController::class, 'details'])->name('movies.details');
Route::get('/favorites', [MovieController::class, 'favorites'])->name('movies.favorites');
Route::get('/fetch', [MovieController::class, 'fetchMovies'])->name('movies.fetch');
Route::get('/movies/searchMovies', [MovieController::class, 'searchMovies'])->name('movies.searchMovies');




