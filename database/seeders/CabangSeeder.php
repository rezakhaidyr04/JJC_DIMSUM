<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Seed default branch data.
     */
    public function run(): void
    {
        $branches = [
            ['kode_cabang' => 'CBG-01', 'nama_cabang' => 'Cabang Cikampek Barat'],
            ['kode_cabang' => 'CBG-02', 'nama_cabang' => 'Cabang Cikampek Timur'],
            ['kode_cabang' => 'CBG-03', 'nama_cabang' => 'Cabang Purwasari'],
            ['kode_cabang' => 'CBG-04', 'nama_cabang' => 'Cabang Karawang Kota'],
            ['kode_cabang' => 'CBG-05', 'nama_cabang' => 'Cabang Klari'],
            ['kode_cabang' => 'CBG-06', 'nama_cabang' => 'Cabang Telukjambe'],
            ['kode_cabang' => 'CBG-07', 'nama_cabang' => 'Cabang Jatisari'],
            ['kode_cabang' => 'CBG-08', 'nama_cabang' => 'Cabang Dawuan'],
            ['kode_cabang' => 'CBG-09', 'nama_cabang' => 'Cabang Kotabaru'],
            ['kode_cabang' => 'CBG-10', 'nama_cabang' => 'Cabang Rengasdengklok'],
        ];

        foreach ($branches as $branch) {
            Cabang::updateOrCreate(
                ['kode_cabang' => $branch['kode_cabang']],
                [
                    'nama_cabang' => $branch['nama_cabang'],
                    'aktif' => true,
                ]
            );
        }
    }
}
