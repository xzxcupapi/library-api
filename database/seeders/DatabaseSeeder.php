<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Kunjungan;
use App\Models\Buku;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();
         Mahasiswa::factory(10)->create();
        Buku::factory()->count(500)->create();
        // Kunjungan::factory()->count(1000)->create();
    }
}
