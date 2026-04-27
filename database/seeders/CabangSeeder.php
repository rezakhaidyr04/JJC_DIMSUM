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
            ['kode_cabang' => 'CAB-01', 'nama_cabang' => 'Cab 1 Pawarengan'],
            ['kode_cabang' => 'CAB-02', 'nama_cabang' => 'Cab 2 Regency'],
            ['kode_cabang' => 'CAB-03', 'nama_cabang' => 'Cab 3 Angkringan Sukaseri'],
            ['kode_cabang' => 'CAB-04', 'nama_cabang' => 'Cab 4 Angkringan Pawarengan'],
            ['kode_cabang' => 'CAB-05', 'nama_cabang' => 'Cab 5 Stand HK Kamojing'],
            ['kode_cabang' => 'CAB-06', 'nama_cabang' => 'Cab 6 Cikopak Purwakarta'],
            ['kode_cabang' => 'CAB-07', 'nama_cabang' => 'Cab 7 Munjul Purwakarta'],
            ['kode_cabang' => 'CAB-08', 'nama_cabang' => 'Cab 8 Telor Gulung Niceso Senopati'],
            ['kode_cabang' => 'CAB-09', 'nama_cabang' => 'Cab 9 O!Save Sukaseri'],
            ['kode_cabang' => 'CAB-10', 'nama_cabang' => 'Cab 10 Maracang Purwakarta'],
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
