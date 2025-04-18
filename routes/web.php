<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\FavoriteController;

Route::get('/', function () {
    return view('home');
});

Route::get('/favourites', function () {
    return view('favourites');
});

Route::get('/reviews', function () {
    return view('reviews');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/bikes', [BikeController::class, 'index'])->name('bike.index');

Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/bikes', [BikeController::class, 'search'])->name('bikes.search');
Route::get('/bikes/{bikeId}', [BikeController::class, 'show'])->name('bikes.show');

Route::post('/favorite', [FavoriteController::class, 'store'])->middleware('auth');
Route::get('/favorites', [FavoriteController::class, 'index'])->middleware('auth')->name('favorites.index');
