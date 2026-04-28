<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class AdditionalCabangSeeder extends Seeder
{
    /**
     * Seed user-provided branches.
     */
    public function run(): void
    {
        $branches = [
            ['kode_cabang' => 'CBG-11', 'nama_cabang' => 'Cab 1 pawarengan'],
            ['kode_cabang' => 'CBG-12', 'nama_cabang' => 'Cab 2 regency'],
            ['kode_cabang' => 'CBG-13', 'nama_cabang' => 'Cab 3 Angkringan sukaseri'],
            ['kode_cabang' => 'CBG-14', 'nama_cabang' => 'Cab 4 Angkringan pawarengan'],
            ['kode_cabang' => 'CBG-15', 'nama_cabang' => 'Cab 5 Stand HK Kamojing'],
            ['kode_cabang' => 'CBG-16', 'nama_cabang' => 'Cab 6 Cikopak purwakarta'],
            ['kode_cabang' => 'CBG-17', 'nama_cabang' => 'Cab 7 Munjul purwakarta'],
            ['kode_cabang' => 'CBG-18', 'nama_cabang' => 'Cab 8 Telor gulung niceso senopati'],
            ['kode_cabang' => 'CBG-19', 'nama_cabang' => 'Cab 9 O!save sukaseri'],
            ['kode_cabang' => 'CBG-20', 'nama_cabang' => 'Cab 10 Maracang purwakarta'],
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
