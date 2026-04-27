<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LokasiPenyimpanan;
use Illuminate\Database\Seeder;

class LokasiPenyimpananSeeder extends Seeder
{
    /**
     * Seed lokasi penyimpanan for all cabangs
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        // Default lokasi untuk setiap cabang
        $defaultLokasi = [
            ['nama_lokasi' => 'Gudang 1', 'tipe' => 'gudang', 'deskripsi' => 'Gudang penyimpanan utama'],
            ['nama_lokasi' => 'Rak A', 'tipe' => 'rak', 'deskripsi' => 'Rak penyimpanan A'],
            ['nama_lokasi' => 'Rak B', 'tipe' => 'rak', 'deskripsi' => 'Rak penyimpanan B'],
            ['nama_lokasi' => 'Display', 'tipe' => 'custom', 'deskripsi' => 'Area display barang'],
        ];

        foreach ($cabangs as $cabang) {
            foreach ($defaultLokasi as $lokasi) {
                LokasiPenyimpanan::updateOrCreate(
                    [
                        'cabang_id' => $cabang->id,
                        'nama_lokasi' => $lokasi['nama_lokasi'],
                    ],
                    [
                        'tipe' => $lokasi['tipe'],
                        'deskripsi' => $lokasi['deskripsi'],
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}
