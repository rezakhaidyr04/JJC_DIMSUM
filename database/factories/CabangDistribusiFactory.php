<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\CabangDistribusi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CabangDistribusi>
 */
class CabangDistribusiFactory extends Factory
{
    protected $model = CabangDistribusi::class;

    public function definition(): array
    {
        return [
            'tanggal' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'cabang_id' => Cabang::factory(),
            'user_id' => User::factory(),
            'catatan' => $this->faker->optional()->sentence(),
        ];
    }
}
