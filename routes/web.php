<?php

use App\Http\Controllers\SeasonController;
use App\Http\Controllers\CheckInController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome page (for guests, before login)
Route::get('/', function () {
    return view('welcome');
});

// Everything below needs login
Route::middleware(['auth', 'verified'])->group(function () {
    // This is where Breeze sends users after login/register
    Route::get('/dashboard', [SeasonController::class, 'index'])
        ->name('dashboard');

    // ===== CareerSeason routes =====

    // Season flow
    Route::get('/season/start', [SeasonController::class, 'create'])
        ->name('season.create');

    Route::post('/season', [SeasonController::class, 'store'])
        ->name('season.store');

    Route::get('/season/current', [SeasonController::class, 'showCurrent'])
        ->name('season.current');

    Route::get('/season/report', [SeasonController::class, 'report'])
        ->name('season.report');

    // Weekly check-ins
    Route::get('/season/current/checkin', [CheckInController::class, 'create'])
        ->name('checkin.create');

    Route::post('/season/current/checkin', [CheckInController::class, 'store'])
        ->name('checkin.store');

    Route::get('/season/week/{week}', [CheckInController::class, 'show'])
        ->name('checkin.show');

    // Your Seasons - view all seasons
    Route::get('/seasons', [SeasonController::class, 'indexAll'])
        ->name('seasons.index');

    // View specific season
    Route::get('/seasons/{season}', [SeasonController::class, 'showSeason'])
        ->name('seasons.show');

    // Report for a specific season
    Route::get('/seasons/{season}/report', [SeasonController::class, 'reportForSeason'])
        ->name('seasons.report');
});

// Public Season report – shareable without login
Route::get('/s/{token}', [SeasonController::class, 'publicReport'])
    ->name('season.public');

// Breeze auth routes (login, register, etc.)
require __DIR__.'/auth.php';
