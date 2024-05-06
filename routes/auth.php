<?php

use App\Http\Controllers\FrontEnd\AuthenticatedSessionController;
use App\Http\Controllers\FrontEnd\RegisteredUserController;
use App\Http\Controllers\FrontEnd\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReviewController::class, 'index'])
    ->name('dashboard');

Route::get('review/crate', [ReviewController::class, 'createReview'])
    ->middleware('auth:sanctum')
    ->name('review.create');

Route::get('review/{id}', [ReviewController::class, 'show'])
    ->name('review.show');

Route::middleware('guest')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

});


