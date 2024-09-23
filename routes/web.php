<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mahasiswa.partials.app');
})->name('home');
