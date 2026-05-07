<?php

namespace Database\Factories;

use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cabang>
 */
class CabangFactory extends Factory
{
    protected $model = Cabang::class;

    public function definition(): array
    {
        return [
            'nama_cabang' => $this->faker->unique()->company(),
            'kode_cabang' => $this->faker->unique()->bothify('CAB-##'),
            'alamat' => $this->faker->address(),
            'aktif' => true,
        ];
    }
}
