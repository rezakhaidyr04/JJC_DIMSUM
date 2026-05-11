<?php

namespace App\Console\Commands;

use App\Services\LowStockAlertService;
use Illuminate\Console\Command;

class SendLowStockAlerts extends Command
{
    protected $signature = 'stocks:notify-low';

    protected $description = 'Send low stock alerts to owner via email and optional WhatsApp';

    public function handle(LowStockAlertService $service): int
    {
        $items = $service->getLowStockItems();

        if ($items->isEmpty()) {
            $this->info('Tidak ada barang stok rendah atau habis saat ini.');

            return self::SUCCESS;
        }

        $message = $service->buildMessage($items);
        $emailResult = $service->sendEmailToOwnersWithItems($items);
        $whatsAppResult = $service->sendWhatsApp($message);

        $this->info('Notifikasi stok rendah berhasil diproses.');
        $this->line('Tanggal: '.now()->locale('id')->translatedFormat('d F Y'));
        $this->line('Jumlah barang terdeteksi: '.$items->count());
        $this->line('Email terkirim ke: '.(empty($emailResult['sent']) ? '-' : implode(', ', $emailResult['sent'])));

        if (! empty($emailResult['failed'])) {
            foreach ($emailResult['failed'] as $failed) {
                $this->error('Email gagal ke '.$failed['email'].' | '.$failed['reason']);
            }
        }

        if ($whatsAppResult === null) {
            $this->line('WhatsApp: dilewati karena konfigurasi belum aktif.');
        } else {
            $this->line('WhatsApp: '.($whatsAppResult['ok'] ? 'berhasil' : 'gagal').' (HTTP '.$whatsAppResult['status'].')');
        }

        return self::SUCCESS;
    }
}
