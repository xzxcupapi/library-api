<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\KunjunganController;
use App\Http\Controllers\Api\PeminjamanController;

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
        Route::get('/{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.id');
        Route::put('/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
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
        Route::put('/{id}', [BukuController::class, 'update'])->name('buku.update');
        Route::get('/{id}', [BukuController::class, 'show'])->name('buku.id');
        Route::delete('/{id}', [BukuController::class, 'destroy'])->name('buku.delete');
    });
});
Route::prefix('buku')->group(function () {
    Route::get('/', [BukuController::class, 'getAll'])->name('buku.index');
    Route::get('/search', [BukuController::class, 'searchByJudul'])->name('buku.search');
});

// PEMINJAMAN
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('peminjaman')->group(function () {
        Route::post('/', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/', [PeminjamanController::class, 'getAll'])->name('peminjaman.index');
        Route::put('/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
        Route::delete('/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.delete');
    });
});
