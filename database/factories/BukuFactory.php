<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar nama pengarang dan penerbit yang berasal dari Indonesia
        $pengarangIndo = [
            'Ayu Utami',
            'Pramoedya Ananta Toer',
            'Tere Liye',
            'Andrea Hirata',
            'Habiburrahman El Shirazy',
            'Soe Hok Gie',
            'Leila S. Chudori',
            'Dewi Lestari',
            'Sapardi Djoko Damono',
            'Risa Sigit',
            'Joko Pinurbo',
            'Okky Madasari'
        ];

        $penerbitIndo = [
            'Gramedia',
            'Bentang Pustaka',
            'Elex Media Komputindo',
            'Mizan',
            'Buku Kompas',
            'Penerbit Erlangga',
            'Buku Prima',
            'Penerbit Haru',
            'Penerbit Republika',
            'KPG (Kepustakaan Populer Gramedia)'
        ];

        $judul = rtrim($this->faker->sentence(3), '.');

        return [
            'judul' => $judul,
            'pengarang' => $this->faker->randomElement($pengarangIndo),
            'penerbit' => $this->faker->randomElement($penerbitIndo),
            'tahun_terbit' => $this->faker->year(),
            'status' => $this->faker->randomElement(['tersedia', 'hilang']),
        ];
    }
}
