<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)
    ->prefix('dashboard')
    ->name('dashboard.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('payrolls', 'payrolls')->name('payrolls');
        Route::get('summary', 'summary')->name('summary');
    });