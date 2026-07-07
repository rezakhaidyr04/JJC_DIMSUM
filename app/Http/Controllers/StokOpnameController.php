<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\CabangDistribusi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StokOpnameController extends Controller
{
    /**
     * Form input operasional cabang harian (karyawan).
     */
    public function index(Request $request): View
    {
        $barangList = Barang::orderBy('nama_barang')->get(['id_barang', 'nama_barang', 'stok']);
        // Urutan prioritas cabang sesuai permintaan user. Nama harus sama persis dengan data DB.
        $preferredOrder = [
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

        // Ambil semua cabang aktif dari DB terlebih dahulu
        $allCabangs = Cabang::where('aktif', true)->get();
        $inactiveCabangs = Cabang::where('aktif', false)->get();

        // Bangun daftar terurut sesuai preferensi user. Jika nama preferensi tidak ditemukan,
        // tetap sertakan entri dengan 'model' = null sehingga masih terlihat di UI.
        $orderedCabangs = collect($preferredOrder)->map(function ($preferredName) use ($allCabangs) {
            $found = $allCabangs->first(function ($c) use ($preferredName) {
                return strcasecmp(trim($c->nama_cabang), trim($preferredName)) === 0;
            });

            return [
                'preferred_name' => $preferredName,
                'model' => $found, // bisa null
            ];
        });

        // Hanya tampilkan cabang yang masuk daftar prioritas user.
        $cabangList = $orderedCabangs->pluck('model')->filter()->values();

        $selectedTanggal = old('tanggal', $request->query('tanggal', now()->toDateString()));
        $selectedCabang = old('cabang_id', $request->query('cabang_id'));

        $selectedHeader = null;
        if ($selectedCabang) {
            $selectedHeader = CabangDistribusi::with('items')
                ->whereDate('tanggal', $selectedTanggal)
                ->where('cabang_id', $selectedCabang)
                ->where('user_id', auth()->id())
                ->latest()
                ->first();
        }

        $existingItemsByBarang = $selectedHeader
            ? $selectedHeader->items->keyBy('barang_id')
            : collect();

        $todayRecords = CabangDistribusi::with(['cabang', 'items.barang'])
            ->whereDate('tanggal', now()->toDateString())
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('stok_opname.index', [
            'barangList' => $barangList,
            'cabangList' => $cabangList,
            'inactiveCabangs' => $inactiveCabangs,
            'todayRecords' => $todayRecords,
            'selectedTanggal' => $selectedTanggal,
            'selectedCabang' => $selectedCabang,
            'existingItemsByBarang' => $existingItemsByBarang,
        ]);
    }

    /**
     * Show the pagi+malam input page for a specific cabang (karyawan view).
     */
    public function showCabang(Request $request, Cabang $cabang): View
    {
        $barangList = Barang::orderBy('nama_barang')->get(['id_barang', 'nama_barang', 'stok']);

        $selectedTanggal = $request->query('tanggal', now()->toDateString());
        $selectedCabang = $cabang->id_cabang;

        $selectedHeader = CabangDistribusi::with('items')
            ->whereDate('tanggal', $selectedTanggal)
            ->where('cabang_id', $selectedCabang)
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        $existingItemsByBarang = $selectedHeader
            ? $selectedHeader->items->keyBy('barang_id')
            : collect();

        $todayRecords = CabangDistribusi::with(['cabang', 'items.barang'])
            ->whereDate('tanggal', now()->toDateString())
            ->where('user_id', auth()->id())
            ->latest()
            ->get();


        $recentActivities = CabangDistribusi::with(['user', 'items.barang'])
            ->where('cabang_id', $cabang->id_cabang)
            ->latest()
            ->take(8)
            ->get()
            ->map(function (CabangDistribusi $record) {
                $items = $record->items;

                return [
                    'tanggal' => $record->tanggal,
                    'created_at' => $record->created_at,
                    'user_name' => $record->user?->name ?? '-',
                    'total_bawa' => $items->sum('jumlah_bawa'),
                    'total_sisa' => $items->sum('jumlah_sisa'),
                    'total_terpakai' => $items->sum('jumlah_terpakai'),
                    'barang_keluar_count' => $items->whereNotNull('barang_keluar_id')->count(),
                    'barang_masuk_count' => $items->whereNotNull('barang_masuk_id')->count(),
                ];
            });

        return view('stok_opname.cabang', [
            'barangList' => $barangList,
            'cabang' => $cabang,
            'selectedTanggal' => $selectedTanggal,
            'selectedCabang' => $selectedCabang,
            'existingItemsByBarang' => $existingItemsByBarang,
            'todayRecords' => $todayRecords,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Simpan barang dibawa ke cabang (input pagi).
     */
    public function storeBerangkat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cabang_id' => ['required', 'exists:cabangs,id_cabang'],
            'tanggal' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'berangkat' => ['required', 'array', 'min:1'],
            'berangkat.*.barang_id' => ['required', 'exists:barang,id_barang'],
            'berangkat.*.jumlah_bawa' => ['required', 'integer', 'min:0'],
        ]);

        $rows = collect($validated['berangkat'])
            ->filter(function (array $row) {
                return ((int) $row['jumlah_bawa']) > 0;
            })
            ->values();

        if ($rows->isEmpty()) {
            return redirect()->back()->withInput()->withErrors([
                'berangkat' => 'Minimal ada satu barang yang dibawa ke cabang pada input pagi.',
            ]);
        }

        DB::transaction(function () use ($validated, $rows) {
            $header = $this->findOrCreateHeader($validated['tanggal'], (int) $validated['cabang_id']);

            if (! empty($validated['catatan'])) {
                $header->update(['catatan' => $validated['catatan']]);
            }

            foreach ($rows as $row) {
                $barang = Barang::findOrFail($row['barang_id']);
                $jumlahBawa = (int) $row['jumlah_bawa'];
                $item = $header->items()->where('barang_id', $barang->id_barang)->first();

                if ($item && $item->jumlah_sisa > $jumlahBawa) {
                    abort(422, 'Jumlah dibawa tidak boleh kurang dari jumlah sisa yang sudah tercatat malam hari.');
                }

                if ($item && $item->barang_keluar_id) {
                    $barangKeluar = BarangKeluar::find($item->barang_keluar_id);

                    if ($barangKeluar) {
                        $selisihBawa = $jumlahBawa - $item->jumlah_bawa;

                        if ($selisihBawa > 0) {
                            $barang->decrement('stok', $selisihBawa);
                        } elseif ($selisihBawa < 0) {
                            $barang->increment('stok', abs($selisihBawa));
                        }

                        if ($jumlahBawa === 0) {
                            $barangKeluar->delete();
                            $item->barang_keluar_id = null;
                        } else {
                            $barangKeluar->update([
                                'jumlah' => $jumlahBawa,
                                'tanggal_keluar' => $validated['tanggal'],
                                'updated_at' => now(),
                            ]);
                        }
                    }
                } else {
                    $barangKeluar = BarangKeluar::create([
                        'barang_id' => $barang->id_barang,
                        'user_id' => auth()->id(),
                        'jumlah' => $jumlahBawa,
                        'tanggal_keluar' => $validated['tanggal'],
                        'void_status' => 'none',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $barang->decrement('stok', $jumlahBawa);
                    if ($item) {
                        $item->barang_keluar_id = $barangKeluar->id_barang_keluar;
                    }
                }

                $jumlahSisa = (int) ($item->jumlah_sisa ?? 0);
                $jumlahTerpakai = $jumlahBawa - $jumlahSisa;

                if (! $item) {
                    $header->items()->create([
                        'barang_id' => $barang->id_barang,
                        'jumlah_bawa' => $jumlahBawa,
                        'jumlah_sisa' => 0,
                        'jumlah_terpakai' => $jumlahBawa,
                        'barang_keluar_id' => $barangKeluar->id_barang_keluar ?? null,
                        'barang_masuk_id' => null,
                    ]);
                } else {
                    $item->update([
                        'jumlah_bawa' => $jumlahBawa,
                        'jumlah_terpakai' => $jumlahTerpakai,
                        'barang_keluar_id' => $item->barang_keluar_id,
                    ]);
                }
            }
        });

        return redirect()
            ->route('stok-opname.index', [
                'tanggal' => $validated['tanggal'],
                'cabang_id' => $validated['cabang_id'],
            ])
            ->with('success', 'Input pagi berhasil disimpan. Barang keluar sudah otomatis tercatat.');
    }

    /**
     * Simpan barang sisa dari cabang (input malam).
     */
    public function storeSisa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cabang_id' => ['required', 'exists:cabangs,id_cabang'],
            'tanggal' => ['required', 'date'],
            'sisa' => ['required', 'array', 'min:1'],
            'sisa.*.barang_id' => ['required', 'exists:barang,id_barang'],
            'sisa.*.jumlah_sisa' => ['required', 'integer', 'min:0'],
        ]);

        $header = CabangDistribusi::with('items')
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('cabang_id', $validated['cabang_id'])
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        if (! $header) {
            return redirect()->back()->withInput()->withErrors([
                'sisa' => 'Input malam tidak bisa disimpan. Silakan input data keberangkatan pagi terlebih dahulu.',
            ]);
        }

        $itemMap = $header->items->keyBy('barang_id');

        DB::transaction(function () use ($validated, $itemMap) {
            foreach ($validated['sisa'] as $row) {
                $barang = Barang::findOrFail($row['barang_id']);
                $jumlahSisaBaru = (int) $row['jumlah_sisa'];
                $item = $itemMap->get($barang->id_barang);

                if (! $item) {
                    if ($jumlahSisaBaru > 0) {
                        abort(422, 'Jumlah sisa hanya bisa diisi untuk barang yang dibawa di pagi hari.');
                    }

                    continue;
                }

                if ($jumlahSisaBaru > $item->jumlah_bawa) {
                    abort(422, 'Jumlah sisa tidak boleh lebih besar dari jumlah dibawa.');
                }

                $jumlahSisaLama = (int) $item->jumlah_sisa;
                $selisihSisa = $jumlahSisaBaru - $jumlahSisaLama;

                if ($selisihSisa !== 0) {
                    if ($item->barang_masuk_id) {
                        $barangMasuk = BarangMasuk::find($item->barang_masuk_id);

                        if ($barangMasuk) {
                            if ($jumlahSisaBaru === 0) {
                                $barang->decrement('stok', $jumlahSisaLama);
                                $barangMasuk->delete();
                                $item->barang_masuk_id = null;
                            } else {
                                $barangMasuk->update([
                                    'jumlah' => $jumlahSisaBaru,
                                    'tanggal_masuk' => $validated['tanggal'],
                                    'updated_at' => now(),
                                ]);

                                if ($selisihSisa > 0) {
                                    $barang->increment('stok', $selisihSisa);
                                } else {
                                    $barang->decrement('stok', abs($selisihSisa));
                                }
                            }
                        }
                    } elseif ($jumlahSisaBaru > 0) {
                        $barangMasuk = BarangMasuk::create([
                            'barang_id' => $barang->id_barang,
                            'user_id' => auth()->id(),
                            'jumlah' => $jumlahSisaBaru,
                            'sumber' => 'sisa_cabang',
                            'tanggal_masuk' => $validated['tanggal'],
                            'void_status' => 'none',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $item->barang_masuk_id = $barangMasuk->id_barang_masuk;
                        $barang->increment('stok', $jumlahSisaBaru);
                    }
                }

                $item->update([
                    'jumlah_sisa' => $jumlahSisaBaru,
                    'jumlah_terpakai' => $item->jumlah_bawa - $jumlahSisaBaru,
                    'barang_masuk_id' => $item->barang_masuk_id,
                ]);
            }
        });

        return redirect()
            ->route('stok-opname.index', [
                'tanggal' => $validated['tanggal'],
                'cabang_id' => $validated['cabang_id'],
            ])
            ->with('success', 'Input malam berhasil disimpan. Barang sisa sudah otomatis tercatat sebagai barang masuk.');
    }

    /**
     * Simpan cabang baru dari halaman stok opname (karyawan).
     */
    public function storeCabang(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:120', 'unique:cabangs,nama_cabang'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $namaCabang = $this->withCabangPrefix($validated['nama_cabang']);
        $kodeCabang = $this->generateCabangKode($namaCabang);

        Cabang::create([
            'nama_cabang' => $namaCabang,
            'kode_cabang' => $kodeCabang,
            'alamat' => $validated['alamat'] ?? null,
            'aktif' => (bool) ($validated['aktif'] ?? true),
        ]);

        return redirect()
            ->route('stok-opname.index', ['tanggal' => $request->query('tanggal')])
            ->with('success', 'Cabang baru berhasil ditambahkan.');
    }

    /**
     * Nonaktifkan cabang dari halaman stok opname (karyawan).
     */
    public function deactivateCabang(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cabang_id' => ['required', 'exists:cabangs,id_cabang'],
        ]);

        Cabang::where('id_cabang', $validated['cabang_id'])
            ->update(['aktif' => false]);

        return redirect()
            ->route('stok-opname.index', ['tanggal' => $request->query('tanggal')])
            ->with('success', 'Cabang berhasil dinonaktifkan.');
    }

    /**
     * Aktifkan kembali cabang dari halaman stok opname (karyawan).
     */
    public function activateCabang(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cabang_id' => ['required', 'exists:cabangs,id_cabang'],
        ]);

        Cabang::where('id_cabang', $validated['cabang_id'])
            ->update(['aktif' => true]);

        return redirect()
            ->route('stok-opname.index', ['tanggal' => $request->query('tanggal')])
            ->with('success', 'Cabang berhasil diaktifkan kembali.');
    }

    /**
     * Rekap operasional cabang harian untuk owner.
     */
    public function rekap(Request $request): View
    {
        abort_unless(auth()->user()?->isOwner(), 403);

        $bulan = $request->query('bulan', now()->format('Y-m'));
        $cabangId = $request->query('cabang_id');
        $periodeMulai = now()->createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
        $periodeSelesai = now()->createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();

        $query = CabangDistribusi::with(['cabang', 'user', 'items.barang'])
            ->whereBetween('tanggal', [$periodeMulai, $periodeSelesai])
            ->latest();

        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }

        $records = $query->get();
        $summaryByCabang = $this->buildSummaryByCabang($records);
        $konsumsiBarang = $this->buildKonsumsiBarang($records);
        // Build ordered cabang list for the rekap view (preserve preferred order)
        $preferredOrder = [
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

        $allCabangs = Cabang::where('aktif', true)->get();

        $orderedCabangs = collect($preferredOrder)->map(function ($preferredName) use ($allCabangs) {
            $found = $allCabangs->first(function ($c) use ($preferredName) {
                return strcasecmp(trim($c->nama_cabang), trim($preferredName)) === 0;
            });

            return [
                'preferred_name' => $preferredName,
                'model' => $found,
            ];
        });

        $otherCabangs = $allCabangs->reject(function ($c) use ($preferredOrder) {
            foreach ($preferredOrder as $p) {
                if (strcasecmp(trim($c->nama_cabang), trim($p)) === 0) {
                    return true;
                }
            }

            return false;
        })->values();

        $cabangList = $orderedCabangs->pluck('model')->filter()->values()->concat($otherCabangs);

        return view('stok_opname.rekap', [
            'records' => $records,
            'summaryByCabang' => $summaryByCabang,
            'konsumsiBarang' => $konsumsiBarang,
            'bulan' => $bulan,
            'periodeMulai' => $periodeMulai,
            'periodeSelesai' => $periodeSelesai,
            'cabangList' => $cabangList,
            'selectedCabang' => $cabangId,
        ]);
    }

    /**
     * Export rekap operasional cabang harian ke PDF untuk owner.
     */
    public function exportPdf(Request $request)
    {
        abort_unless(auth()->user()?->isOwner(), 403);

        $bulan = $request->query('bulan', now()->format('Y-m'));
        $cabangId = $request->query('cabang_id');
        $periodeMulai = now()->createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
        $periodeSelesai = now()->createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();

        $query = CabangDistribusi::with(['cabang', 'user', 'items.barang'])
            ->whereBetween('tanggal', [$periodeMulai, $periodeSelesai])
            ->latest();

        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }

        $records = $query->get();
        $summaryByCabang = $this->buildSummaryByCabang($records);
        $konsumsiBarang = $this->buildKonsumsiBarang($records);

        $pdf = Pdf::loadView('stok_opname.pdf', [
            'records' => $records,
            'summaryByCabang' => $summaryByCabang,
            'konsumsiBarang' => $konsumsiBarang,
            'bulan' => $bulan,
            'periodeMulai' => $periodeMulai,
            'periodeSelesai' => $periodeSelesai,
            'logoBase64' => $this->getLogoBase64(),
        ])->setPaper('a4', 'landscape')->setOption('defaultFont', 'Arial');

        return $pdf->download('rekap-operasional-cabang-bulanan-'.str_replace('-', '', $bulan).'.pdf');
    }

    private function buildSummaryByCabang(Collection $records): Collection
    {
        return $records
            ->groupBy(function (CabangDistribusi $record) {
                return $record->cabang?->nama_cabang ?? '-';
            })
            ->map(function (Collection $group) {
                $items = $group->flatMap(function (CabangDistribusi $record) {
                    return $record->items;
                });

                return [
                    'total_bawa' => $items->sum('jumlah_bawa'),
                    'total_sisa' => $items->sum('jumlah_sisa'),
                    'total_terpakai' => $items->sum('jumlah_terpakai'),
                    'total_transaksi' => $group->count(),
                ];
            });
    }

    private function buildKonsumsiBarang(Collection $records): Collection
    {
        return $records
            ->flatMap(function (CabangDistribusi $record) {
                return $record->items;
            })
            ->groupBy(function ($item) {
                return $item->barang?->nama_barang ?? '-';
            })
            ->map(function (Collection $group) {
                return (int) $group->sum('jumlah_terpakai');
            })
            ->sortDesc()
            ->take(12);
    }

    private function getLogoBase64(): ?string
    {
        $logoPath = public_path('images/logo-login.png');

        if (! file_exists($logoPath)) {
            return null;
        }

        $logoContents = file_get_contents($logoPath);

        if ($logoContents === false) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode($logoContents);
    }

    private function generateCabangKode(string $namaCabang): string
    {
        $parts = preg_split('/\s+/', trim($namaCabang));
        $initials = collect($parts)
            ->filter()
            ->map(function ($part) {
                return Str::upper(Str::substr(preg_replace('/[^a-zA-Z0-9]/', '', $part), 0, 1));
            })
            ->implode('');

        if ($initials === '') {
            $initials = 'CB';
        }

        $initials = Str::upper(Str::substr($initials, 0, 3));

        $attempts = 0;
        do {
            $attempts++;
            $kode = $initials.'-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
            $exists = Cabang::where('kode_cabang', $kode)->exists();
        } while ($exists && $attempts < 10);

        return $kode;
    }

    private function withCabangPrefix(string $namaCabang): string
    {
        $cleanName = trim($namaCabang);
        if (Str::startsWith(Str::lower($cleanName), 'cab ')) {
            return $cleanName;
        }

        $maxNumber = Cabang::query()
            ->where('nama_cabang', 'like', 'Cab %')
            ->get()
            ->map(function (Cabang $cabang) {
                if (preg_match('/^cab\s+(\d+)/i', $cabang->nama_cabang, $matches)) {
                    return (int) $matches[1];
                }

                return null;
            })
            ->filter()
            ->max();

        $nextNumber = (int) ($maxNumber ?? 0) + 1;

        return 'Cab '.$nextNumber.' '.$cleanName;
    }

    private function findOrCreateHeader(string $tanggal, int $cabangId): CabangDistribusi
    {
        return CabangDistribusi::firstOrCreate(
            [
                'tanggal' => $tanggal,
                'cabang_id' => $cabangId,
                'user_id' => auth()->id(),
            ],
            [
                'catatan' => null,
            ]
        );
    }
}
