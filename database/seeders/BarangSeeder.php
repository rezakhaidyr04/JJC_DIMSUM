<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['nama_barang' => 'Sedotan', 'stok' => 100],
            ['nama_barang' => 'Cup', 'stok' => 150],
            ['nama_barang' => 'Sumpit', 'stok' => 200],
            ['nama_barang' => 'Piring', 'stok' => 120],
            ['nama_barang' => 'Gelas', 'stok' => 180],
        ];

        foreach ($items as $item) {
            Barang::create($item);
        }
    }
}
