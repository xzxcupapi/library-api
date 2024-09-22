<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
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

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
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

//BUKU
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('buku')->group(function () {
        Route::post('/', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/', [BukuController::class, 'getAll'])->name('buku.index');
        Route::get('/search', [BukuController::class, 'searchByJudul'])->name('buku.search');
    });
});
