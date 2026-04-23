# 📚 Code Examples & Reference Guide

## Table of Contents
1. [Models](#models)
2. [Controllers](#controllers)
3. [Blade Templates](#blade-templates)
4. [Routes](#routes)
5. [Validation Examples](#validation-examples)
6. [Query Examples](#query-examples)

---

## Models

### Barang Model
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'stok'];

    // Relationships
    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    // Helper Methods
    public function getTotalMasuk()
    {
        return $this->barangMasuk()->sum('jumlah');
    }

    public function getTotalKeluar()
    {
        return $this->barangKeluar()->sum('jumlah');
    }
}
```

### BarangMasuk Model
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $fillable = ['barang_id', 'jumlah', 'tanggal'];
    protected $casts = ['tanggal' => 'date'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
```

---

## Controllers

### DashboardController - Statistics & Charts
```php
<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalMasuk = BarangMasuk::sum('jumlah');
        $totalKeluar = BarangKeluar::sum('jumlah');
        $totalStok = Barang::sum('stok');

        $chartData = $this->getChartData();

        return view('dashboard.index', compact(
            'totalBarang', 'totalMasuk', 'totalKeluar', 
            'totalStok', 'chartData'
        ));
    }

    private function getChartData()
    {
        $days = 7;
        $labels = [];
        $masukData = [];
        $keluarData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $masuk = BarangMasuk::whereDate('tanggal', $date)->sum('jumlah');
            $keluar = BarangKeluar::whereDate('tanggal', $date)->sum('jumlah');

            $masukData[] = $masuk;
            $keluarData[] = $keluar;
        }

        return compact('labels', 'masukData', 'keluarData');
    }
}
```

### BarangController - CRUD Operations
```php
<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::latest()->paginate(10);
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|unique:barang|max:255',
            'stok' => 'required|integer|min:0',
        ]);

        Barang::create($validated);
        return redirect()->route('barang.index')
                       ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => "required|string|unique:barang,nama_barang,{$barang->id}|max:255",
            'stok' => 'required|integer|min:0',
        ]);

        $barang->update($validated);
        return redirect()->route('barang.index')
                       ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')
                       ->with('success', 'Barang berhasil dihapus.');
    }
}
```

### BarangMasukController - Auto-increment Stock
```php
<?php
namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Create record
        BarangMasuk::create($validated);

        // Auto increment stock
        $barang = Barang::find($validated['barang_id']);
        $barang->increment('stok', $validated['jumlah']);

        return redirect()->route('barang-masuk.index')
                       ->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Calculate difference
        $jumlahDiff = $validated['jumlah'] - $barangMasuk->jumlah;

        // Adjust stock
        if ($jumlahDiff != 0) {
            $barang = Barang::find($barangMasuk->barang_id);
            $barang->increment('stok', $jumlahDiff);
        }

        $barangMasuk->update($validated);
        return redirect()->route('barang-masuk.index')
                       ->with('success', 'Barang masuk berhasil diperbarui.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        // Decrease stock when deleting
        $barang = Barang::find($barangMasuk->barang_id);
        $barang->decrement('stok', $barangMasuk->jumlah);

        $barangMasuk->delete();
        return redirect()->route('barang-masuk.index')
                       ->with('success', 'Barang masuk berhasil dihapus.');
    }
}
```

---

## Blade Templates

### Login Form Template
```blade
<!DOCTYPE html>
<html>
<head>
    <title>Login - Stock Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

### Dashboard with Chart.js
```blade
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Barang</h5>
                    <h2>{{ $totalBarang }}</h2>
                </div>
            </div>
        </div>
        <!-- More stat cards -->
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Aktivitas Stok</h3>
                </div>
                <div class="card-body">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: @json($chartData['masukData']),
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($chartData['keluarData']),
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } }
            }
        });
    </script>
    @endpush
@endsection
```

### Data Table with Actions
```blade
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barang as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>
                    <span class="badge bg-info">{{ $item->stok }}</span>
                </td>
                <td>
                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('barang.destroy', $item->id) }}" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
{{ $barang->links() }}
```

---

## Routes

### Full Route Configuration
```php
// routes/web.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('barang', BarangController::class);
    Route::resource('barang-masuk', BarangMasukController::class);
    Route::resource('barang-keluar', BarangKeluarController::class);

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
});

require __DIR__.'/auth.php';
```

---

## Validation Examples

### Store Barang
```php
$request->validate([
    'nama_barang' => 'required|string|unique:barang|max:255',
    'stok' => 'required|integer|min:0',
]);
```

### Store Barang Masuk
```php
$request->validate([
    'barang_id' => 'required|integer|exists:barang,id',
    'jumlah' => 'required|integer|min:1',
    'tanggal' => 'required|date',
]);
```

### User Registration
```php
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users|max:255',
    'password' => 'required|min:8|confirmed',
]);
```

---

## Query Examples

### Get Dashboard Stats
```php
// Total items
$totalBarang = Barang::count();

// Sum of incoming
$totalMasuk = BarangMasuk::sum('jumlah');

// Sum of outgoing
$totalKeluar = BarangKeluar::sum('jumlah');

// Total stock
$totalStok = Barang::sum('stok');
```

### Get Item with Relationships
```php
// Eager load relationships
$barang = Barang::with(['barangMasuk', 'barangKeluar'])->get();

// Get specific item with relations
$item = Barang::with('barangMasuk')->find($id);
```

### Filter by Date
```php
// Get records for specific date
$hari_ini = BarangMasuk::whereDate('tanggal', today())->get();

// Get records for date range
$range = BarangMasuk::whereBetween('tanggal', [
    $startDate, 
    $endDate
])->get();
```

### Stock Adjustments
```php
// Increment stock
$barang->increment('stok', 10);

// Decrement stock
$barang->decrement('stok', 5);

// Set specific value
$barang->update(['stok' => 100]);
```

---

## Common Patterns

### Auto-Increment on Create
```php
public function store(Request $request)
{
    $data = $request->validate([...]);
    
    // Create record
    $model = Model::create($data);
    
    // Auto-increment related stock
    $parent = Parent::find($data['parent_id']);
    $parent->increment('stok', $data['jumlah']);
}
```

### Adjust Stock on Update
```php
public function update(Request $request, Model $model)
{
    $oldValue = $model->jumlah;
    $data = $request->validate([...]);
    
    $diff = $data['jumlah'] - $oldValue;
    
    if ($diff != 0) {
        $parent = Parent::find($model->parent_id);
        $parent->increment('stok', $diff);
    }
    
    $model->update($data);
}
```

---

## Error Handling

### Validation Error Display
```blade
@error('field_name')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
```

### Flash Messages
```blade
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
```

### Check Empty Collections
```blade
@forelse($items as $item)
    <li>{{ $item->name }}</li>
@empty
    <p>Tidak ada data</p>
@endforelse
```

---

## Useful Blade Directives

```blade
<!-- Conditional -->
@if ($condition)
    ...
@elseif ($other)
    ...
@else
    ...
@endif

<!-- Loop -->
@foreach ($items as $item)
    {{ $loop->iteration }} - {{ $item->name }}
@endforeach

<!-- Loop with alternative -->
@forelse ($items as $item)
    ...
@empty
    <p>Tidak ada data</p>
@endforelse

<!-- Auth Check -->
@auth
    <p>User: {{ Auth::user()->name }}</p>
@endauth

@guest
    <p>Silakan login</p>
@endguest

<!-- Include Views -->
@include('components.header')

<!-- Extend Layout -->
@extends('layouts.app')

<!-- Define Section -->
@section('content')
    ...
@endsection
```

---

This guide should help you understand and work with the application code!
