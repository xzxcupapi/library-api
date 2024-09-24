<?php

namespace Database\Factories;

use App\Models\Kunjungan;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class KunjunganFactory extends Factory
{
    protected $model = Kunjungan::class;

    public function definition()
    {
        // // Ambil semua id mahasiswa dari database
        // $mahasiswaIds = Mahasiswa::pluck('id')->toArray();

        // // Menghasilkan tanggal dari bulan Agustus hingga hari kemarin, kecuali hari Minggu
        // $startDate = \Carbon\Carbon::createFromDate(2024, 8, 1); // Tanggal 1 Agustus 2024
        // $endDate = \Carbon\Carbon::yesterday(); // Hari kemarin
        // $dateRange = [];

        // // Loop untuk mendapatkan semua tanggal dalam rentang waktu yang ditentukan
        // while ($startDate->lte($endDate)) {
        //     if ($startDate->dayOfWeek !== \Carbon\Carbon::SUNDAY) { // Mengecek jika bukan hari Minggu
        //         $dateRange[] = $startDate->format('Y-m-d');
        //     }
        //     $startDate->addDay(); // Tambah satu hari
        // }

        // return [
        //     'id_mahasiswa' => $this->faker->randomElement($mahasiswaIds), // Ambil id mahasiswa secara acak
        //     'tanggal_kunjungan' => $this->faker->randomElement($dateRange), // Ambil tanggal acak dari rentang yang telah ditentukan
        // ];
    }
}
