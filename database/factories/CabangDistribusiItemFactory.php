<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\CabangDistribusi;
use App\Models\CabangDistribusiItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CabangDistribusiItem>
 */
class CabangDistribusiItemFactory extends Factory
{
    protected $model = CabangDistribusiItem::class;

    public function definition(): array
    {
        $jumlahBawa = $this->faker->numberBetween(1, 20);
        $jumlahSisa = $this->faker->numberBetween(0, $jumlahBawa);

        return [
            'cabang_distribusi_id' => CabangDistribusi::factory(),
            'barang_id' => Barang::factory(),
            'jumlah_bawa' => $jumlahBawa,
            'jumlah_sisa' => $jumlahSisa,
            'jumlah_terpakai' => $jumlahBawa - $jumlahSisa,
            'barang_keluar_id' => null,
            'barang_masuk_id' => null,
        ];
    }
}
