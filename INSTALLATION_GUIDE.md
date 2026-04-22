# Sistem Informasi Stok Barang Dimsum JJC

Sistem manajemen stok barang untuk restoran dimsum berbasis Laravel 10+.

## Deskripsi

Sistem ini digunakan untuk mengelola item stok operasional di restoran dimsum seperti sedotan, cup, sumpit, piring, dan gelas. Sistem ini menggantikan pencatatan stok manual untuk membuat manajemen inventaris lebih akurat, efisien, dan mudah dipantau.

## Teknologi yang Digunakan

- **Laravel 10+** - Framework PHP Modern
- **MySQL** - Database
- **Bootstrap 5** - UI Framework
- **AdminLTE Dashboard** - Dashboard Template
- **Blade Template** - Template Engine
- **Eloquent ORM** - Database ORM
- **Chart.js** - Visualisasi Data
- **Laravel Seeder** - Database Seeding

## Fitur Utama

### 1. Authentication
- Login/Logout
- Registrasi Pengguna
- Auth Middleware untuk proteksi route

### 2. Dashboard
- Tampilkan statistik:
  - Total Barang
  - Total Barang Masuk
  - Total Barang Keluar
  - Total Stok
- Grafik Chart.js menunjukkan aktivitas stok 7 hari terakhir

### 3. Manajemen Barang (CRUD)
- Tambah Barang
- Edit Barang
- Hapus Barang
- Lihat Daftar Barang dengan Pagination

### 4. Barang Masuk
- Catat barang yang masuk ke gudang
- Otomatis menambah stok barang

### 5. Barang Keluar
- Catat barang yang keluar dari gudang
- Otomatis mengurangi stok barang

### 6. Laporan Stok
- Tampilkan laporan lengkap stok barang
- Fitur cetak/print untuk laporan

## Struktur Database

### Tabel `users`
- id (Primary Key)
- name
- email (Unique)
- password
- timestamps

### Tabel `barang`
- id (Primary Key)
- nama_barang (Unique)
- stok
- timestamps

### Tabel `barang_masuk`
- id (Primary Key)
- barang_id (Foreign Key)
- jumlah
- tanggal
- timestamps

### Tabel `barang_keluar`
- id (Primary Key)
- barang_id (Foreign Key)
- jumlah
- tanggal
- timestamps

## Relasi Model

- **Barang** → hasMany BarangMasuk
- **Barang** → hasMany BarangKeluar
- **BarangMasuk** → belongsTo Barang
- **BarangKeluar** → belongsTo Barang

## Struktur File Project

```
stok-dimsum/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   └── RegisteredUserController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── BarangController.php
│   │   │   ├── BarangMasukController.php
│   │   │   ├── BarangKeluarController.php
│   │   │   └── LaporanController.php
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Barang.php
│       ├── BarangMasuk.php
│       └── BarangKeluar.php
├── database/
│   ├── migrations/
│   │   ├── 2024_04_16_000001_create_barang_table.php
│   │   ├── 2024_04_16_000002_create_barang_masuk_table.php
│   │   └── 2024_04_16_000003_create_barang_keluar_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── BarangSeeder.php
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── barang/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── barang_masuk/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── barang_keluar/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   └── laporan/
│   │       └── index.blade.php
├── routes/
│   ├── web.php
│   └── auth.php
├── config/
├── storage/
├── bootstrap/
├── composer.json
├── phpunit.xml
├── vite.config.js
└── README.md
```

## Panduan Instalasi

### Prasyarat
- PHP 8.1 atau lebih tinggi
- Composer
- MySQL Server
- Node.js (Opsional untuk Asset Building)

### Langkah Instalasi

#### 1. Setup Database
```bash
# Buat database MySQL baru
mysql -u root -p
CREATE DATABASE stok_dimsum;
EXIT;
```

#### 2. Konfigurasi Environment
```bash
# Copy file environment
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

#### 3. Edit file `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stok_dimsum
DB_USERNAME=root
DB_PASSWORD=
```

#### 4. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies (jika diperlukan)
npm install
```

#### 5. Jalankan Migrations
```bash
# Jalankan migration untuk membuat table
php artisan migrate
```

#### 6. Jalankan Seeder
```bash
# Jalankan seeder untuk memasukkan data default
php artisan db:seed

# Atau jalankan seeder spesifik
php artisan db:seed --class=BarangSeeder
```

#### 7. Build Assets (Jika diperlukan)
```bash
# Development
npm run dev

# Production
npm run build
```

#### 8. Jalankan Aplikasi
```bash
# Jalankan development server
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

#### 9. Akses Aplikasi

**Register User Baru:**
- Buka: `http://localhost:8000/register`
- Isi form pendaftaran
- Klik tombol "Daftar"

**Login:**
- Buka: `http://localhost:8000/login`
- Masukkan email dan password
- Klik tombol "Login"

**Dashboard:**
- Setelah login, Anda akan diarahkan ke dashboard
- Dashboard menampilkan statistik stok dan grafik

## Rute Aplikasi

| Method | Rute | Deskripsi |
|--------|------|-----------|
| GET | /register | Form Registrasi |
| POST | /register | Proses Registrasi |
| GET | /login | Form Login |
| POST | /login | Proses Login |
| POST | /logout | Logout |
| GET | /dashboard | Dashboard |
| GET | /barang | Daftar Barang |
| GET | /barang/create | Form Tambah Barang |
| POST | /barang | Simpan Barang |
| GET | /barang/{id}/edit | Form Edit Barang |
| PUT | /barang/{id} | Update Barang |
| DELETE | /barang/{id} | Hapus Barang |
| GET | /barang-masuk | Daftar Barang Masuk |
| GET | /barang-masuk/create | Form Tambah Barang Masuk |
| POST | /barang-masuk | Simpan Barang Masuk |
| GET | /barang-masuk/{id}/edit | Form Edit Barang Masuk |
| PUT | /barang-masuk/{id} | Update Barang Masuk |
| DELETE | /barang-masuk/{id} | Hapus Barang Masuk |
| GET | /barang-keluar | Daftar Barang Keluar |
| GET | /barang-keluar/create | Form Tambah Barang Keluar |
| POST | /barang-keluar | Simpan Barang Keluar |
| GET | /barang-keluar/{id}/edit | Form Edit Barang Keluar |
| PUT | /barang-keluar/{id} | Update Barang Keluar |
| DELETE | /barang-keluar/{id} | Hapus Barang Keluar |
| GET | /laporan | Laporan Stok |

## Validasi Form

Semua form dilengkapi dengan validasi Laravel:

### Barang
- `nama_barang`: Required, String, Unique, Max 255
- `stok`: Required, Integer, Min 0

### Barang Masuk
- `barang_id`: Required, Exists di tabel barang
- `jumlah`: Required, Integer, Min 1
- `tanggal`: Required, Date

### Barang Keluar
- `barang_id`: Required, Exists di tabel barang
- `jumlah`: Required, Integer, Min 1
- `tanggal`: Required, Date

### User Registration
- `name`: Required, String, Max 255
- `email`: Required, Email, Unique
- `password`: Required, Min 8, Confirmed

### User Login
- `email`: Required, Email
- `password`: Required

## Data Default (Seeder)

Berikut adalah data barang default yang dimasukkan melalui BarangSeeder:

| Nama Barang | Stok Awal |
|------------|-----------|
| Sedotan | 100 |
| Cup | 150 |
| Sumpit | 200 |
| Piring | 120 |
| Gelas | 180 |

## Tips Penggunaan

1. **Dashboard**: Pantau statistik stok real-time di dashboard
2. **Barang Masuk**: Selalu catat barang yang masuk untuk update stok otomatis
3. **Barang Keluar**: Catat barang yang keluar saat digunakan di restoran
4. **Laporan**: Gunakan fitur laporan untuk audit stok atau presentasi manajemen
5. **Cetak Laporan**: Gunakan tombol "Cetak" untuk print laporan stok

## Troubleshooting

### 1. Database Connection Error
- Pastikan MySQL server berjalan
- Verifikasi konfigurasi di file `.env`
- Pastikan database sudah dibuat

### 2. Artisan Command Error
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Regenerate autoload
composer dump-autoload
```

### 3. Session/Auth Error
```bash
# Buat storage links jika diperlukan
php artisan storage:link
```

### 4. Port 8000 Sudah Digunakan
```bash
# Gunakan port berbeda
php artisan serve --port=8001
```

## Pengembangan Lebih Lanjut

Fitur-fitur yang dapat dikembangkan:
- Export data ke Excel
- Generate PDF laporan
- User roles dan permissions
- Dashboard analitik lebih detail
- API untuk mobile app
- Email notifications
- Backup otomatis database

## Lisensi

MIT License - Silakan gunakan dan kembangkan sesuai kebutuhan.

## Kontak & Support

Untuk pertanyaan atau support, hubungi tim development.

---

**Dibuat dengan ❤️ menggunakan Laravel 10+**
