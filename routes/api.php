<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\KunjunganController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});


// MAHASISWA
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('mahasiswa')->group(function () {
        Route::post('/', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/', [MahasiswaController::class, 'getAllData'])->name('mahasiswa.index');
        Route::get('/search', [MahasiswaController::class, 'searchByNpm'])->name('mahasiswa.search');
        Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });
});

// KUNJUNGAN
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('kunjungan')->group(function () {
        Route::get('/', [KunjunganController::class, 'getAll'])->name('kunjungan.index');
    });
});
Route::prefix('kunjungan')->group(function () {
    Route::post('/', [KunjunganController::class, 'store'])->name('kunjungan.store');
});
