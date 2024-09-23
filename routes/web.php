<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Middleware\CheckToken;


// MAHASISWA
Route::get('/', function () {
    return view('mahasiswa.partials.app');
})->name('home');

// ADMIN AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// ADMIN DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
