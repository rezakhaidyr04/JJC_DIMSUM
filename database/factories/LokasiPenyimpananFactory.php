<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\LokasiPenyimpanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LokasiPenyimpanan>
 */
class LokasiPenyimpananFactory extends Factory
{
    protected $model = LokasiPenyimpanan::class;

    public function definition(): array
    {
        return [
            'cabang_id' => Cabang::factory(),
            'nama_lokasi' => $this->faker->unique()->words(2, true),
            'tipe' => $this->faker->randomElement(['gudang', 'rak', 'custom']),
            'deskripsi' => $this->faker->sentence(),
            'aktif' => true,
        ];
    }
}
