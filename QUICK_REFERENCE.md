# 🚀 QUICK START - FIFO & Lokasi Penyimpanan

## Installation & Setup (5 menit)

### Step 1: Run Migrations
```bash
cd d:\project\ laravel\JJC_DIMSUM
php artisan migrate
```

### Step 2: Seed Data
```bash
php artisan db:seed --class=CabangSeeder
php artisan db:seed --class=LokasiPenyimpananSeeder
```

### Verify Installation
```bash
php artisan tinker
>>> DB::table('cabangs')->count()
10  ✓

>>> DB::table('lokasi_penyimpanans')->count()
40  ✓
```

---

## Key Files Summary

| File | Purpose | Status |
|------|---------|--------|
| `CabangSeeder.php` | 10 cabang data | ✅ Modified |
| `LokasiPenyimpanan.php` | Model lokasi | ✅ Created |
| `FifoService.php` | FIFO logic engine | ✅ Created |
| `RiwayatTransaksiController.php` | Transaction history | ✅ Created |
| `riwayat-transaksi/index.blade.php` | UI view | ✅ Created |

---

## Models Quick Reference

### Barang Model
```php
// Relationships
$barang->cabang           // Get cabang
$barang->lokasiDefault    // Get default location

// Usage
$barang = Barang::with('cabang', 'lokasiDefault')->find(1);
```

### LokasiPenyimpanan Model
```php
// Relationships
$lokasi->cabang           // Get parent cabang
$lokasi->barangItems      // Get barang stored here

// Get full name
echo $lokasi->nama_lengkap;  // "Cab 1 Pawarengan - Gudang 1"

// Active locations only
$lokasi = LokasiPenyimpanan::aktif()->get();
```

### FifoService
```php
use App\Services\FifoService;

$fifo = app(FifoService::class);

// Get candidates for withdrawal (oldest first)
$candidates = $fifo->getFifoCandidates(
    barangId: 1,
    lokasiId: 5,
    quantity: 20
);

// Get remaining stock for a barang_masuk
$remaining = $fifo->getRemainingStock(barangMasukId: 42);

// Get stock breakdown by tanggal_masuk
$details = $fifo->getStockDetails(barangId: 1, lokasiId: 5);

// Create FIFO withdrawal (MAIN METHOD)
$keluarRecords = $fifo->createFifoWithdrawal([
    'barang_id' => 1,
    'lokasi_id' => 5,
    'cabang_id' => 2,
    'jumlah' => 15,
    'tanggal_keluar' => '2026-04-27',
    'user_id' => auth()->id(),
]);
```

---

## Common Tasks

### 1. Create Barang Masuk (with location)
```php
$masuk = BarangMasuk::create([
    'barang_id' => 1,
    'cabang_id' => 1,
    'lokasi_id' => 1,  // New!
    'jumlah' => 100,
    'tanggal_masuk' => now(),  // Renamed from 'tanggal'
    'user_id' => auth()->id(),
]);
```

### 2. Create Barang Keluar with FIFO
```php
use App\Services\FifoService;

$fifo = app(FifoService::class);

// Don't create BarangKeluar manually!
// Use FifoService to handle FIFO logic
$keluarRecords = $fifo->createFifoWithdrawal([
    'barang_id' => 1,
    'lokasi_id' => 1,
    'cabang_id' => 1,
    'jumlah' => 30,
    'tanggal_keluar' => now(),
    'user_id' => auth()->id(),
]);
```

### 3. Get Transaction History
```php
// Visit: /riwayat-transaksi
// Or query directly:

$masuk = BarangMasuk::with('barang', 'cabang', 'lokasi')
    ->where('cabang_id', 1)
    ->orderByDesc('created_at')
    ->get();

$keluar = BarangKeluar::with('barang', 'cabang', 'lokasi', 'barangMasukFifo')
    ->where('cabang_id', 1)
    ->orderByDesc('created_at')
    ->get();
```

### 4. Check Stock with FIFO Info
```php
$fifo = app(FifoService::class);

$stockDetails = $fifo->getStockDetails(
    barangId: 1,
    lokasiId: 1
);

foreach ($stockDetails as $detail) {
    echo "Tanggal Masuk: {$detail['tanggal_masuk']}";
    echo "Masuk: {$detail['jumlah_masuk']}";
    echo "Keluar: {$detail['jumlah_keluar']}";
    echo "Sisa: {$detail['sisa_stok']}";
}
```

### 5. Get Oldest Stock (FIFO)
```php
$fifo = app(FifoService::class);

$candidates = $fifo->getFifoCandidates(
    barangId: 1,
    lokasiId: 1,
    quantity: 999
);

foreach ($candidates as $candidate) {
    echo "ID: {$candidate->id}";
    echo "Tanggal: {$candidate->tanggal_masuk}";
    echo "Umur: {$candidate->tanggal_masuk->diffInDays(now())} hari";
}
```

---

## Database Queries

### All Cabangs
```sql
SELECT * FROM cabangs WHERE aktif = true;
```

### All Lokasi with Cabang
```sql
SELECT l.id, c.nama_cabang, l.nama_lokasi, l.tipe
FROM lokasi_penyimpanans l
JOIN cabangs c ON l.cabang_id = c.id
ORDER BY c.nama_cabang, l.nama_lokasi;
```

### FIFO Stock Breakdown
```sql
SELECT 
    bm.id,
    bm.tanggal_masuk,
    bm.jumlah,
    COALESCE(SUM(bk.jumlah), 0) as jumlah_keluar,
    bm.jumlah - COALESCE(SUM(bk.jumlah), 0) as sisa
FROM barang_masuk bm
LEFT JOIN barang_keluar bk ON bk.barang_masuk_id = bm.id
WHERE bm.barang_id = 1 AND bm.lokasi_id = 1
GROUP BY bm.id
ORDER BY bm.tanggal_masuk ASC;
```

### Transaction History
```sql
-- Barang Masuk
SELECT 'MASUK' as tipe, bm.tanggal_masuk as tanggal, b.nama_barang, c.nama_cabang, l.nama_lokasi, bm.jumlah, u.name
FROM barang_masuk bm
JOIN barang b ON b.id = bm.barang_id
JOIN cabangs c ON c.id = bm.cabang_id
JOIN lokasi_penyimpanans l ON l.id = bm.lokasi_id
JOIN users u ON u.id = bm.user_id

UNION ALL

-- Barang Keluar
SELECT 'KELUAR' as tipe, bk.tanggal_keluar as tanggal, b.nama_barang, c.nama_cabang, l.nama_lokasi, bk.jumlah, u.name
FROM barang_keluar bk
JOIN barang b ON b.id = bk.barang_id
JOIN cabangs c ON c.id = bk.cabang_id
JOIN lokasi_penyimpanans l ON l.id = bk.lokasi_id
JOIN users u ON u.id = bk.user_id

ORDER BY tanggal DESC;
```

---

## Common Errors & Solutions

### Error: "Insufficient stock for FIFO withdrawal"
```php
// Problem: Tidak ada barang_masuk yang cukup untuk qty yang diminta
// Solution: Check available stock with
$fifo->getFifoCandidates($barangId, $lokasiId, $requestedQty);
```

### Error: "barang_masuk_id cannot be null"
```php
// Problem: Creating BarangKeluar manually instead of using FifoService
// Solution: Use FifoService::createFifoWithdrawal() instead
```

### Error: "lokasi_id does not exist"
```php
// Problem: Invalid lokasi_id selected
// Solution: Get valid lokasi from:
LokasiPenyimpanan::where('cabang_id', $cabangId)->get();
```

---

## Routes

| Route | Method | Purpose |
|-------|--------|---------|
| `/riwayat-transaksi` | GET | View transaction history with filters |
| `/barang-masuk` | GET | View barang masuk |
| `/barang-keluar` | GET | View barang keluar |
| `/dashboard` | GET | Dashboard with low stock notifications |

---

## Testing

```bash
# Run tests (if available)
php artisan test

# Check syntax
php artisan tinker
>>> echo "System ready!";

# View logs
tail -f storage/logs/laravel.log
```

---

## Troubleshooting

### Migrations not running?
```bash
php artisan migrate:fresh --seed
```

### Want to rollback?
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback all
php artisan migrate:reset

# Refresh (reset + migrate + seed)
php artisan migrate:fresh --seed
```

### Clear cache?
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Getting Help

1. **Check docs:** `DOKUMENTASI_FIFO_LOKASI.md`
2. **Check logs:** `storage/logs/laravel.log`
3. **Tinker:** `php artisan tinker`
4. **Database:** Direct SQL queries

---

**Quick Start Complete!** 🎉  
Ready to use FIFO & Lokasi Penyimpanan system.
