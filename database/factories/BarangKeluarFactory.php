<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarangKeluar>
 */
class BarangKeluarFactory extends Factory
{
    protected $model = BarangKeluar::class;

    public function definition(): array
    {
        return [
            'barang_id' => Barang::factory(),
            'user_id' => User::factory(),
            'cabang_id' => null,
            'lokasi_id' => null,
            'barang_masuk_id' => null,
            'jumlah' => $this->faker->numberBetween(1, 20),
            'tanggal_keluar' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'void_status' => 'none',
            'void_reason' => null,
            'void_requested_by' => null,
            'void_requested_at' => null,
            'void_approved_by' => null,
            'void_approved_at' => null,
        ];
    }
}
