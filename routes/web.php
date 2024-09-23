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
// mahasiswa
Route::get('/mahasiswa', function () {
    return view('admin.pages.mahasiswa.index');
})->name('mahasiswa.all');
// buku 
Route::get('/buku', function () {
    return view('admin.pages.buku.index');
})->name('buku.all');
