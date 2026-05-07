<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarangMasuk>
 */
class BarangMasukFactory extends Factory
{
    protected $model = BarangMasuk::class;

    public function definition(): array
    {
        return [
            'barang_id' => Barang::factory(),
            'user_id' => User::factory(),
            'cabang_id' => null,
            'lokasi_id' => null,
            'jumlah' => $this->faker->numberBetween(1, 20),
            'sumber' => 'manual',
            'tanggal_masuk' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'void_status' => 'none',
            'void_reason' => null,
            'void_requested_by' => null,
            'void_requested_at' => null,
            'void_approved_by' => null,
            'void_approved_at' => null,
        ];
    }
}
