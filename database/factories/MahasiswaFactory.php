<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'npm' => $this->faker->unique()->numerify('##########'),
            'nama_lengkap' => fake('id_ID')->name(),
            'fakultas' => $this->faker->randomElement(['Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Ilmu Sosial', 'Fakultas Hukum']),
            'sidik_jari' => substr($this->faker->uuid, 0, 5)
        ];
    }
}
