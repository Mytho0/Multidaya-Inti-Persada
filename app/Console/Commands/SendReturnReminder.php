<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReturnReminder extends Command
{
    protected $signature = 'reminder:send-return';
    protected $description = 'Kirim pengingat pengembalian barang via WhatsApp';
    
    protected $whatsappService;
    
    public function __construct(WhatsAppService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }
    
    public function handle()
    {
        // Ambil peminjaman yang akan kembali dalam 1-3 hari
        $reminders = Peminjaman::where('status_pengembalian', 'aktif')
            ->where('whatsapp_sent_pengingat', false)
            ->whereBetween('tanggal_kembali', [
                Carbon::now()->addDays(1)->startOfDay(),
                Carbon::now()->addDays(3)->endOfDay()
            ])
            ->get();
        
        $this->info('Found ' . $reminders->count() . ' peminjaman to remind');
        
        foreach ($reminders as $peminjaman) {
            $this->info('Sending reminder to: ' . $peminjaman->nama_penyewa);
            
            $result = $this->whatsappService->sendPengingatPengembalian($peminjaman);
            
            if ($result['success']) {
                $peminjaman->update([
                    'whatsapp_sent_pengingat' => true,
                    'whatsapp_pengingat_sent_at' => now()
                ]);
                $this->info('Reminder sent successfully');
            } else {
                $this->error('Failed to send reminder: ' . ($result['message'] ?? 'Unknown error'));
            }
        }
        
        $this->info('Reminder sending completed');
    }
}