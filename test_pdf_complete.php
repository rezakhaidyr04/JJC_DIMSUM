<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BarangMasuk, App\Models\BarangKeluar, Barryvdh\DomPDF\Facade\Pdf;

echo "=== PDF Export Test ===\n\n";

// Test 1: Check DomPDF
echo "1. Testing DomPDF...\n";
try {
    $testPdf = Pdf::loadHtml('<h1>Test</h1>');
    echo "   ✅ DomPDF works\n";
} catch (\Exception $e) {
    echo "   ❌ DomPDF error: " . $e->getMessage() . "\n";
}

// Test 2: Check BarangMasuk data
echo "\n2. Testing BarangMasuk relationship...\n";
try {
    $masuk = BarangMasuk::with('barang', 'user')->first();
    if ($masuk) {
        echo "   ✅ BarangMasuk found: " . $masuk->id . "\n";
        echo "   - Barang: " . ($masuk->barang ? $masuk->barang->nama_barang : 'NULL') . "\n";
        echo "   - User: " . ($masuk->user ? $masuk->user->name : 'NULL') . "\n";
    } else {
        echo "   ⚠️ No BarangMasuk records\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 3: Check BarangKeluar data  
echo "\n3. Testing BarangKeluar relationship...\n";
try {
    $keluar = BarangKeluar::with('barang', 'user')->first();
    if ($keluar) {
        echo "   ✅ BarangKeluar found: " . $keluar->id . "\n";
        echo "   - Barang: " . ($keluar->barang ? $keluar->barang->nama_barang : 'NULL') . "\n";
        echo "   - User: " . ($keluar->user ? $keluar->user->name : 'NULL') . "\n";
    } else {
        echo "   ⚠️ No BarangKeluar records\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
