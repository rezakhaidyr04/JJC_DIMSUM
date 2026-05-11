# JJC DIMSUM - Sistem Informasi Stok

Sistem manajemen stok barang untuk operasional restoran dimsum. Dibangun dengan Laravel 10+, mendukung FIFO, multi-cabang, stok opname, dan laporan harian.

## Fitur Utama
- Manajemen barang masuk/keluar dengan histori transaksi
- FIFO untuk pengeluaran stok
- Stok opname harian per cabang
- Laporan stok harian dengan ekspor PDF
- Notifikasi stok rendah
- Notifikasi stok otomatis ke owner via WhatsApp
- Role-based access (owner dan karyawan)

## Teknologi
- Laravel 10+
- MySQL
- Bootstrap 5 + AdminLTE
- Chart.js

## Akun Default
Seeder `UserRoleSeeder` membuat akun berikut:

- Owner
	- Email: owner@jjc-dimsum.test
	- Password: password123
- Karyawan
	- Email: karyawan@jjc-dimsum.test
	- Password: password123

Jika akun belum ada:

```bash
php artisan migrate
php artisan db:seed
```

Untuk notifikasi otomatis, pastikan scheduler Laravel berjalan di server:

```bash
php artisan schedule:run
```

Dan tambahkan cron server untuk menjalankan `schedule:run` tiap menit.

Jika ingin WhatsApp aktif, isi konfigurasi `WHATSAPP_ALERT_ENABLED`, `WHATSAPP_API_URL`, `WHATSAPP_API_TOKEN`, dan `WHATSAPP_OWNER_NUMBER` di file `.env`.

## Instalasi dan Setup
Lihat panduan lengkap di [INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md).

## Dokumentasi Tambahan
- [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)
- [DOKUMENTASI_FIFO_LOKASI.md](DOKUMENTASI_FIFO_LOKASI.md)
- [QUICK_START.md](QUICK_START.md)

## Format Pesan Commit
Gunakan format berikut agar riwayat commit konsisten:

- feat: fitur baru
- fix: perbaikan bug
- style: perubahan UI tanpa ubah logika
- refactor: perapian struktur kode
- docs: perubahan dokumentasi
- chore: pekerjaan pendukung

Contoh:

```text
feat(auth): tambah validasi login role owner
fix(barang): perbaiki hitung stok saat hapus barang masuk
style(login): sesuaikan warna form dengan logo
```
