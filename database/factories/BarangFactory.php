<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        return [
            'nama_barang' => $this->faker->unique()->words(2, true),
            'stok' => 0,
            'stok_min' => 5,
            'satuan' => null,
            'status' => null,
            'kode_barang' => null,
        ];
    }
}
