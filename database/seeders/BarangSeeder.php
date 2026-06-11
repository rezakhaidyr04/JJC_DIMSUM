<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Peralatan Makan
            ['kode_barang' => 'SMT001', 'nama_barang' => 'Sedotan Plastik', 'satuan' => 'pack', 'stok_min' => 10, 'stok' => 500, 'status' => 'active'],
            ['kode_barang' => 'CUP001', 'nama_barang' => 'Cup Plastik 12oz', 'satuan' => 'pack', 'stok_min' => 15, 'stok' => 800, 'status' => 'active'],
            ['kode_barang' => 'CUP002', 'nama_barang' => 'Cup Plastik 16oz', 'satuan' => 'pack', 'stok_min' => 12, 'stok' => 600, 'status' => 'active'],
            ['kode_barang' => 'SPK001', 'nama_barang' => 'Sumpit Kayu', 'satuan' => 'pack', 'stok_min' => 20, 'stok' => 1000, 'status' => 'active'],
            ['kode_barang' => 'PIR001', 'nama_barang' => 'Piring Kertas 7"', 'satuan' => 'pack', 'stok_min' => 10, 'stok' => 600, 'status' => 'active'],
            ['kode_barang' => 'PIR002', 'nama_barang' => 'Piring Kertas 9"', 'satuan' => 'pack', 'stok_min' => 10, 'stok' => 500, 'status' => 'active'],
            ['kode_barang' => 'GLS001', 'nama_barang' => 'Gelas Plastik 12oz', 'satuan' => 'pack', 'stok_min' => 8, 'stok' => 400, 'status' => 'active'],
            
            // Kemasan Makanan
            ['kode_barang' => 'KMS001', 'nama_barang' => 'Kotak Makanan Putih S', 'satuan' => 'pack', 'stok_min' => 10, 'stok' => 400, 'status' => 'active'],
            ['kode_barang' => 'KMS002', 'nama_barang' => 'Kotak Makanan Putih M', 'satuan' => 'pack', 'stok_min' => 10, 'stok' => 350, 'status' => 'active'],
            ['kode_barang' => 'KMS003', 'nama_barang' => 'Kotak Makanan Putih L', 'satuan' => 'pack', 'stok_min' => 8, 'stok' => 300, 'status' => 'active'],
            ['kode_barang' => 'PLK001', 'nama_barang' => 'Plastik Wrap Roll', 'satuan' => 'roll', 'stok_min' => 5, 'stok' => 50, 'status' => 'active'],
            ['kode_barang' => 'ALU001', 'nama_barang' => 'Alumunium Foil', 'satuan' => 'roll', 'stok_min' => 3, 'stok' => 30, 'status' => 'active'],
            
            // Bahan Makanan
            ['kode_barang' => 'DIM001', 'nama_barang' => 'Dimsum Ayam', 'satuan' => 'pcs', 'stok_min' => 50, 'stok' => 500, 'status' => 'active'],
            ['kode_barang' => 'DIM002', 'nama_barang' => 'Dimsum Udang', 'satuan' => 'pcs', 'stok_min' => 30, 'stok' => 300, 'status' => 'active'],
            ['kode_barang' => 'DIM003', 'nama_barang' => 'Dimsum Babi', 'satuan' => 'pcs', 'stok_min' => 20, 'stok' => 200, 'status' => 'active'],
            ['kode_barang' => 'BKW001', 'nama_barang' => 'Bakso Kuah', 'satuan' => 'pcs', 'stok_min' => 40, 'stok' => 400, 'status' => 'active'],
            ['kode_barang' => 'MIN001', 'nama_barang' => 'Teh Manis', 'satuan' => 'liter', 'stok_min' => 10, 'stok' => 100, 'status' => 'active'],
            ['kode_barang' => 'MIN002', 'nama_barang' => 'Kopi Hitam', 'satuan' => 'liter', 'stok_min' => 8, 'stok' => 80, 'status' => 'active'],
            ['kode_barang' => 'MIN003', 'nama_barang' => 'Sirup Strawberry', 'satuan' => 'botol', 'stok_min' => 5, 'stok' => 30, 'status' => 'active'],
            
            // Peralatan Dapur
            ['kode_barang' => 'PNG001', 'nama_barang' => 'Penggorengan 30cm', 'satuan' => 'pcs', 'stok_min' => 2, 'stok' => 10, 'status' => 'active'],
            ['kode_barang' => 'PTH001', 'nama_barang' => 'Panci Besar', 'satuan' => 'pcs', 'stok_min' => 2, 'stok' => 8, 'status' => 'active'],
            ['kode_barang' => 'SPT001', 'nama_barang' => 'Spatula Stainless', 'satuan' => 'pcs', 'stok_min' => 3, 'stok' => 15, 'status' => 'active'],
            
            // Supplies
            ['kode_barang' => 'HDS001', 'nama_barang' => 'Hand Soap 5L', 'satuan' => 'jerigen', 'stok_min' => 2, 'stok' => 10, 'status' => 'active'],
            ['kode_barang' => 'TJL001', 'nama_barang' => 'Tissue Gulung', 'satuan' => 'pack', 'stok_min' => 5, 'stok' => 50, 'status' => 'active'],
        ];

        foreach ($items as $item) {
            Barang::updateOrCreate(
                ['kode_barang' => $item['kode_barang']],
                $item
            );
        }
    }
}
