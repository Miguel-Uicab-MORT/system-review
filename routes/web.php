<?php

use App\Http\Controllers\BackEnd\AuthenticatedSessionController;
use App\Http\Controllers\BackEnd\RegisteredUserController;
use App\Http\Controllers\BackEnd\ReviewController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function () {

    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('api.reviews.index');

    Route::get('/review/{id}', [ReviewController::class, 'show'])
        ->name('api.review.show');
});

Route::group(['prefix' => 'api', 'middleware' => 'guest'], function () {
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('api.login');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('api.register');
});

Route::group(['prefix' => 'api', 'middleware' => 'auth:sanctum'], function () {

        Route::post('/review/create-review', [ReviewController::class, 'store'])
            ->name('api.review.store');

        Route::post('/review/add-comment', [ReviewController::class, 'addComment'])
            ->name('api.review.add-comment');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('api.logout');
});


require __DIR__.'/auth.php';
