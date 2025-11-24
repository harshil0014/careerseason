<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\CheckInController;

// Home -> if no season, show start page; else go to dashboard
Route::get('/', [SeasonController::class, 'index'])->name('home');

// Start a new Season (only one active for now)
Route::get('/season/start', [SeasonController::class, 'create'])->name('season.create');
Route::post('/season', [SeasonController::class, 'store'])->name('season.store');

// View current Season dashboard
Route::get('/season/current', [SeasonController::class, 'showCurrent'])->name('season.current');

// Weekly check-in
Route::get('/season/current/checkin', [CheckInController::class, 'create'])->name('checkin.create');
Route::post('/season/current/checkin', [CheckInController::class, 'store'])->name('checkin.store');

// View a specific week's verdict
Route::get('/season/week/{week}', [CheckInController::class, 'show'])->name('checkin.show');

// Simple Season Report
Route::get('/season/report', [SeasonController::class, 'report'])->name('season.report');

// Public Season Report via token
Route::get('/s/{token}', [SeasonController::class, 'publicReport'])->name('season.public');
