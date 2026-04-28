<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CleanCabangSeeder extends Seeder
{
    /**
     * Hapus semua cabang yang TIDAK ada di daftar keep.
     */
    public function run(): void
    {
        $keep = [
            'Cab 1 pawarengan',
            'Cab 2 regency',
            'Cab 3 Angkringan sukaseri',
            'Cab 4 Angkringan pawarengan',
            'Cab 5 Stand HK Kamojing',
            'Cab 6 Cikopak purwakarta',
            'Cab 7 Munjul purwakarta',
            'Cab 8 Telor gulung niceso senopati',
            'Cab 9 O!save sukaseri',
            'Cab 10 Maracang purwakarta',
        ];

        $toDelete = Cabang::whereNotIn('nama_cabang', $keep)->get();

        foreach ($toDelete as $c) {
            echo "Deleting: {$c->id} - {$c->nama_cabang}\n";
        }

        $count = Cabang::whereNotIn('nama_cabang', $keep)->delete();

        echo "Deleted count: {$count}\n";
    }
}
