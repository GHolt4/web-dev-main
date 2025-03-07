<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BikeController;
use App\Services\NinetyNineSpokesService;
use App\Http\Controllers\SimpleBikesController;

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

Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [SessionController::class, 'create']);
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/bikes', [BikeController::class, 'search'])->name('bikes.search');
Route::get('/bikes/{bikeId}', [BikeController::class, 'show'])->name('bikes.show');

Route::post('/bikes/store', [BikeController::class, 'store'])->name('bikes.store');