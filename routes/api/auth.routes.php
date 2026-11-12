<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    // POST /api/tokens -> sign in (AuthController@store), 5 attempts per minute
    Route::post('/tokens', 'store')->middleware('throttle:5,1')->name('tokens.store');

    Route::middleware('auth:sanctum')->group(function () {
        // DELETE /api/tokens -> sign out (AuthController@destroy)
        Route::delete('/tokens', 'destroy')->name('tokens.destroy');

        // GET /api/user -> the signed-in user (AuthController@show)
        Route::get('/user', 'show')->name('user.show');
    });
});