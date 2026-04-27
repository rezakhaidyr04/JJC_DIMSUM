# 📦 Sistem Manajemen Stok Barang - FIFO & Lokasi Penyimpanan

## Daftar Isi
1. [Ringkasan Perubahan](#ringkasan-perubahan)
2. [1. Perbaikan Data Cabang](#1-perbaikan-data-cabang)
3. [2. Fitur Lokasi Penyimpanan](#2-fitur-lokasi-penyimpanan)
4. [3. Sistem FIFO](#3-sistem-fifo)
5. [4. Riwayat Transaksi](#4-riwayat-transaksi)
6. [Database Schema](#database-schema)
7. [API & Model Relationships](#api--model-relationships)
8. [Testing & Deployment](#testing--deployment)

---

## Ringkasan Perubahan

Implementasi 4 fitur utama untuk meningkatkan sistem manajemen stok:

| Fitur | Status | Files Modified |
|-------|--------|-----------------|
| ✅ Perbaikan Data Cabang | DONE | CabangSeeder.php |
| ✅ Lokasi Penyimpanan | DONE | 1 Migration + 1 Model + 1 Seeder |
| ✅ Sistem FIFO | DONE | 2 Migrations + FifoService.php + Model Updates |
| ✅ Riwayat Transaksi | DONE | Controller + View + Route |

---

## 1. Perbaikan Data Cabang

### Data Cabang yang Benar (10 cabang)
```
CAB-01 → Cab 1 Pawarengan
CAB-02 → Cab 2 Regency
CAB-03 → Cab 3 Angkringan Sukaseri
CAB-04 → Cab 4 Angkringan Pawarengan
CAB-05 → Cab 5 Stand HK Kamojing
CAB-06 → Cab 6 Cikopak Purwakarta
CAB-07 → Cab 7 Munjul Purwakarta
CAB-08 → Cab 8 Telor Gulung Niceso Senopati
CAB-09 → Cab 9 O!Save Sukaseri
CAB-10 → Cab 10 Maracang Purwakarta
```

### File Modified
- `database/seeders/CabangSeeder.php` - Update seed data dengan nama cabang yang benar

### Cara Update Database
```bash
# Clear existing data dan re-seed
php artisan db:seed --class=CabangSeeder
```

---

## 2. Fitur Lokasi Penyimpanan

### Konsep
Setiap barang dapat disimpan di berbagai lokasi penyimpanan yang tersebar di setiap cabang. Lokasi dapat berupa:
- **Gudang** - Penyimpanan utama
- **Rak** - Sub-storage dalam gudang
- **Custom** - Lokasi khusus (Display, Freezer, dll)

### Database Structure

#### Table: `lokasi_penyimpanans`
```sql
CREATE TABLE lokasi_penyimpanans (
    id BIGINT PRIMARY KEY,
    cabang_id BIGINT NOT NULL,
    nama_lokasi VARCHAR(100) NOT NULL,      -- Gudang 1, Rak A, dll
    tipe ENUM('gudang', 'rak', 'custom'),
    deskripsi VARCHAR(255),
    aktif BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (cabang_id) REFERENCES cabangs(id),
    UNIQUE (cabang_id, nama_lokasi)
);
```

### Default Lokasi per Cabang
Sistem otomatis membuat 4 lokasi untuk setiap cabang:
1. **Gudang 1** (tipe: gudang)
2. **Rak A** (tipe: rak)
3. **Rak B** (tipe: rak)
4. **Display** (tipe: custom)

### Models & Relationships

#### LokasiPenyimpanan Model
```php
class LokasiPenyimpanan extends Model
{
    public function cabang() // Cabang that owns this location
    public function barangItems() // Barang stored at this location
    
    // Scope
    public function scopeAktif($query) // Get active locations only
    
    // Attribute
    public function getNamaLengkapAttribute() // Format: "Cabang - Lokasi"
}
```

#### Updates to Other Models

**Barang Model**
```php
class Barang extends Model
{
    public function cabang() // Cabang this barang belongs to
    public function lokasiDefault() // Default storage location
}
```

**Cabang Model**
```php
class Cabang extends Model
{
    public function lokasiPenyimpanans() // All locations in this cabang
}
```

### Seeder
- `database/seeders/LokasiPenyimpananSeeder.php` - Membuat default lokasi untuk semua cabang

### Cara Setup
```bash
# Run migrations
php artisan migrate

# Seed lokasi penyimpanan
php artisan db:seed --class=LokasiPenyimpananSeeder
```

---

## 3. Sistem FIFO (First In First Out)

### Konsep
Sistem FIFO memastikan barang yang **pertama kali masuk (tanggal masuk paling lama)** harus **dikeluarkan terlebih dahulu**. Ini penting untuk:
- ✅ Mencegah barang kadaluarsa
- ✅ Tracking umur stok secara akurat
- ✅ Compliance dengan standar inventory

### Implementation

#### Tracking Data
Setiap transaksi barang sekarang menyimpan:

**BarangMasuk**
```php
- barang_id         // ID barang
- cabang_id         // Cabang penerima
- lokasi_id         // Lokasi penyimpanan
- jumlah            // Qty masuk
- tanggal_masuk     // Tanggal masuk (PENTING untuk FIFO)
- user_id           // Siapa yang input
- created_at        // Waktu dicatat
```

**BarangKeluar**
```php
- barang_id         // ID barang
- cabang_id         // Cabang pengeluaran
- lokasi_id         // Lokasi penyimpanan
- jumlah            // Qty keluar
- tanggal_keluar    // Tanggal keluar
- barang_masuk_id   // ⭐ Link ke BarangMasuk (FIFO reference)
- user_id           // Siapa yang output
- created_at        // Waktu dicatat
```

#### FIFO Service Class

**File:** `app/Services/FifoService.php`

Methods:
```php
class FifoService
{
    // Get candidates untuk withdrawal (ordered by tanggal_masuk asc)
    public function getFifoCandidates(int $barangId, int $lokasiId, int $qty)
    
    // Hitung sisa stok untuk barang_masuk record
    public function getRemainingStock(int $barangMasukId)
    
    // Get detail stok breakdown by tanggal_masuk
    public function getStockDetails(int $barangId, int $lokasiId)
    
    // Create barang_keluar records dengan FIFO
    public function createFifoWithdrawal(array $data)
}
```

### Workflow FIFO

```
User Request Pengeluaran: Qty 15 barang X di Gudang 1
                             ↓
       FifoService.createFifoWithdrawal()
                             ↓
    Get FIFO Candidates (sorted by tanggal_masuk ASC)
    ─────────────────────────────────────────────────
    BarangMasuk #1: 2023-01-01 → Qty: 5  → Sisa: 5 ✓
    BarangMasuk #2: 2023-02-15 → Qty: 8  → Sisa: 8 ✓
    BarangMasuk #3: 2023-03-20 → Qty: 10 → Sisa: 10
                             ↓
    Create BarangKeluar Records:
    1. Qty 5  from BarangMasuk #1 (oldest)
    2. Qty 8  from BarangMasuk #2
    3. Qty 2  from BarangMasuk #3 (remaining)
       Total = 15 ✓
```

### Example Usage dalam Controller

```php
use App\Services\FifoService;

class BarangKeluarController extends Controller
{
    public function store(Request $request, FifoService $fifoService)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang',
            'lokasi_id' => 'required|exists:lokasi_penyimpanans',
            'cabang_id' => 'required|exists:cabangs',
            'jumlah' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
        ]);

        try {
            // Create FIFO withdrawal
            $keluarRecords = $fifoService->createFifoWithdrawal([
                'barang_id' => $validated['barang_id'],
                'lokasi_id' => $validated['lokasi_id'],
                'cabang_id' => $validated['cabang_id'],
                'jumlah' => $validated['jumlah'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route('barang-keluar.index')
                ->with('success', "Pengeluaran barang berhasil (FIFO)");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

---

## 4. Riwayat Transaksi

### Fitur
Menampilkan complete transaction history dengan:
- ✅ Barang Masuk & Keluar dalam satu view
- ✅ Filter by: Barang, Cabang, Tipe, Tanggal
- ✅ FIFO Info display (tanggal masuk & umur stok)
- ✅ User tracking (siapa yang input/output)
- ✅ Pagination

### Files

#### Controller
- `app/Http/Controllers/RiwayatTransaksiController.php`
  - Method: `index()` - Get filtered transactions

#### View
- `resources/views/riwayat-transaksi/index.blade.php`
  - Filter form
  - Transaction cards (masuk/keluar)
  - FIFO info display
  - Pagination

#### Route
```php
Route::get('/riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])
    ->name('riwayat-transaksi');
```

#### Sidebar Menu
Sudah ditambahkan ke `resources/views/layouts/app.blade.php` dengan icon `fa-history`

### Display Format

```
┌─ Transaksi Card ─────────────────────────┐
│ 📥 MASUK                    01 Apr 2026   │
│ ─────────────────────────────────────── │
│ Barang:      Sedotan                     │
│ Cabang:      Cab 1 Pawarengan            │
│ Lokasi:      Gudang 1                    │
│ Jumlah:      [100]                       │
│ Input Oleh:  [Owner]                     │
└──────────────────────────────────────────┘

┌─ Transaksi Card ─────────────────────────┐
│ 📤 KELUAR                   02 Apr 2026   │
│ ─────────────────────────────────────── │
│ Barang:      Sedotan                     │
│ Cabang:      Cab 2 Regency               │
│ Lokasi:      Rak A                       │
│ Jumlah:      [30]                        │
│ Output Oleh: [Karyawan]                  │
│ ─────────────────────────────────────── │
│ 📋 Info FIFO                             │
│ Barang Masuk: 01 Apr 2026                │
│ Umur Stok: 1 hari                        │
└──────────────────────────────────────────┘
```

### Filter Capabilities
- **Barang**: Filter by produk tertentu
- **Cabang**: Filter by lokasi cabang
- **Tipe**: Masuk / Keluar / Semua
- **Tanggal**: Range (dari - sampai)

---

## Database Schema

### Migrations Created

1. **2026_04_27_000011_create_lokasi_penyimpanans_table.php**
   - Create `lokasi_penyimpanans` table

2. **2026_04_27_000012_add_cabang_and_lokasi_to_barang_table.php**
   - Add `cabang_id` & `lokasi_default_id` to `barang`

3. **2026_04_27_000013_add_cabang_lokasi_to_barang_masuk_table.php**
   - Add `cabang_id`, `lokasi_id` to `barang_masuk`
   - Rename `tanggal` → `tanggal_masuk`

4. **2026_04_27_000014_add_cabang_lokasi_fifo_to_barang_keluar_table.php**
   - Add `cabang_id`, `lokasi_id`, `barang_masuk_id` to `barang_keluar`
   - Rename `tanggal` → `tanggal_keluar`

5. **2026_04_27_000015_set_default_cabang_lokasi.php**
   - Set default values untuk existing records

### ER Diagram

```
cabangs
    │
    ├─ lokasi_penyimpanans (1:N)
    │       │
    │       └─ barang (lokasi_default_id) (1:N)
    │
    └─ barang (cabang_id) (1:N)
            │
            ├─ barang_masuk (1:N)
            │       │
            │       └─ barang_keluar (barang_masuk_id) (N:1)
            │
            └─ barang_keluar (1:N)
                    │
                    └─ barang_masuk (barang_masuk_id) (N:1)
```

---

## API & Model Relationships

### Barang Model
```php
$barang->cabang              // Get cabang
$barang->lokasiDefault       // Get default location
$barang->barangMasuk         // Get all incoming shipments
$barang->barangKeluar        // Get all outgoing shipments
```

### LokasiPenyimpanan Model
```php
$lokasi->cabang              // Get parent cabang
$lokasi->barangItems         // Get all barang stored here
$lokasi->namaLengkap         // Get "Cabang - Lokasi" format
```

### BarangMasuk Model
```php
$masuk->barang               // Get barang info
$masuk->cabang               // Get receiving cabang
$masuk->lokasi               // Get storage location
$masuk->user                 // Get who entered this
$masuk->barangKeluarFifo()   // Get all withdrawals using this (FIFO)
```

### BarangKeluar Model
```php
$keluar->barang              // Get barang info
$keluar->cabang              // Get releasing cabang
$keluar->lokasi              // Get storage location
$keluar->user                // Get who released this
$keluar->barangMasukFifo     // Get source barang_masuk (FIFO reference)
```

### FifoService Methods
```php
$fifo = app(FifoService::class);

$fifo->getFifoCandidates(
    barangId: 1,
    lokasiId: 5,
    quantity: 15
) // Returns: Collection of BarangMasuk (oldest first)

$fifo->getRemainingStock(barangMasukId: 42)
// Returns: int (remaining qty after withdrawals)

$fifo->getStockDetails(barangId: 1, lokasiId: 5)
// Returns: Collection with breakdown by tanggal_masuk

$fifo->createFifoWithdrawal([
    'barang_id' => 1,
    'lokasi_id' => 5,
    'cabang_id' => 2,
    'jumlah' => 15,
    'tanggal_keluar' => '2026-04-27',
    'user_id' => 1,
])
// Returns: Array of created BarangKeluar records
```

---

## Testing & Deployment

### Pre-Deployment Checklist

- [ ] Backup existing database
- [ ] Test migrations on staging database
- [ ] Verify cabang data is correct (10 cabang seeded)
- [ ] Verify lokasi_penyimpanan created for all cabang
- [ ] Test FIFO logic with sample data
- [ ] Test Riwayat Transaksi filters
- [ ] Test for data consistency

### Migration Commands

```bash
# Run all pending migrations
php artisan migrate

# Seed cabangs dengan data baru
php artisan db:seed --class=CabangSeeder

# Seed lokasi penyimpanan
php artisan db:seed --class=LokasiPenyimpananSeeder

# Or run all seeders
php artisan db:seed
```

### Test Data Queries

```sql
-- Verify cabangs
SELECT * FROM cabangs WHERE aktif = true;

-- Verify lokasi per cabang
SELECT c.nama_cabang, l.nama_lokasi, l.tipe 
FROM lokasi_penyimpanans l
JOIN cabangs c ON l.cabang_id = c.id
ORDER BY c.nama_cabang, l.nama_lokasi;

-- Test FIFO - get candidates
SELECT bm.id, bm.tanggal_masuk, bm.jumlah,
       (bm.jumlah - COALESCE(SUM(bk.jumlah), 0)) as sisa_stok
FROM barang_masuk bm
LEFT JOIN barang_keluar bk ON bk.barang_masuk_id = bm.id 
WHERE bm.barang_id = 1 AND bm.lokasi_id = 1
GROUP BY bm.id
ORDER BY bm.tanggal_masuk ASC;
```

---

## Troubleshooting

### Masalah: Lokasi tidak muncul di dropdown
**Solusi**: Run seeder `php artisan db:seed --class=LokasiPenyimpananSeeder`

### Masalah: FIFO withdrawal gagal dengan error "Insufficient stock"
**Solusi**: Pastikan ada barang_masuk yang belum selesai dikeluarkan di lokasi tersebut

### Masalah: Data cabang lama masih ada
**Solusi**: Manual delete old cabangs atau refresh dengan `php artisan db:seed --class=CabangSeeder`

---

## Future Enhancements

1. **Stock Monitoring Dashboard**
   - Real-time stock levels by location
   - FIFO age tracking
   - Expiry warnings

2. **Stock Transfer**
   - Antar-cabang transfer
   - Antar-lokasi transfer dalam cabang
   - FIFO-aware transfer logic

3. **Audit Trails**
   - Complete edit history
   - Permission-based access
   - Void request with approval flow

4. **Advanced Reporting**
   - FIFO analysis report
   - Stock age distribution
   - Location utilization report

5. **Barcode Integration**
   - Auto-populate cabang/lokasi
   - Batch FIFO processing
   - Mobile app integration

---

## Support & Documentation

- **Laravel Version**: 10+
- **Database**: MySQL 8.0+
- **PHP Version**: 8.1+

For more help, check application logs:
```bash
tail -f storage/logs/laravel.log
```
