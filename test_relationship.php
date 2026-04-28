<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BarangMasuk;

try {
    $item = BarangMasuk::with('barang', 'user')->first();
    if ($item) {
        echo "✅ BarangMasuk loaded successfully\n";
        echo "Barang: " . ($item->barang ? $item->barang->nama_barang : 'null') . "\n";
        echo "User: " . ($item->user ? $item->user->name : 'null') . "\n";
    } else {
        echo "⚠️ No data found\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
