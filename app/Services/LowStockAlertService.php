<?php

namespace App\Services;

use App\Mail\LowStockAlertMail;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Throwable;

class LowStockAlertService
{
    public function getLowStockItems(): Collection
    {
        return Barang::query()
            ->whereNotNull('stok_min')
            ->whereColumn('stok', '<=', 'stok_min')
            ->orderBy('stok', 'asc')
            ->orderBy('nama_barang', 'asc')
            ->get(['id', 'nama_barang', 'stok', 'stok_min']);
    }

    public function buildMessage(Collection $items): string
    {
        $date = now()->locale('id')->translatedFormat('d F Y');
        $time = now()->format('H:i');

        $lines = [
            'Notifikasi Stok Barang JJC DIMSUM',
            "Tanggal: {$date}",
            "Waktu: {$time}",
            '',
            'Daftar barang stok rendah/habis:',
        ];

        foreach ($items as $item) {
            $status = (int) $item->stok === 0 ? 'HABIS' : 'RENDAH';

            $lines[] = sprintf(
                '- %s | Stok: %d | Minimal: %d | Status: %s',
                $item->nama_barang,
                (int) $item->stok,
                (int) $item->stok_min,
                $status
            );
        }

        $lines[] = '';
        $lines[] = 'Segera lakukan pengecekan dan pengisian stok jika diperlukan.';

        return implode(PHP_EOL, $lines);
    }

    public function sendEmailToOwnersWithItems(Collection $items): array
    {
        $recipients = User::query()
            ->where('role', 'owner')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->values();

        if ($recipients->isEmpty()) {
            return [
                'sent' => [],
                'failed' => [],
            ];
        }

        $dateLabel = now()->locale('id')->translatedFormat('d F Y');
        $timeLabel = now()->format('H:i');

        $formattedItems = $items->map(function ($item) {
            return [
                'nama_barang' => $item->nama_barang,
                'stok' => (int) $item->stok,
                'stok_min' => (int) $item->stok_min,
                'status_label' => (int) $item->stok === 0 ? 'HABIS' : 'RENDAH',
            ];
        })->all();

        $sent = [];
        $failed = [];

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new LowStockAlertMail(
                    items: $formattedItems,
                    dateLabel: $dateLabel,
                    timeLabel: $timeLabel,
                ));

                $sent[] = $email;
            } catch (Throwable $exception) {
                $failed[] = [
                    'email' => $email,
                    'reason' => $exception->getMessage(),
                ];
            }
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
        ];
    }

    public function sendWhatsApp(string $message): ?array
    {
        if (! config('services.whatsapp.enabled')) {
            return null;
        }

        $apiUrl = config('services.whatsapp.api_url');
        $token = config('services.whatsapp.token');
        $to = config('services.whatsapp.to');

        if (! $apiUrl || ! $to) {
            return null;
        }

        $payload = [
            'to' => $to,
            'message' => $message,
        ];

        $request = Http::timeout(20);

        if ($token) {
            $request = $request->withToken($token);
        }

        $response = $request->post($apiUrl, $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
}