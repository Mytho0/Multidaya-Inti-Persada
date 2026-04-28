<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;
    
    public function __construct()
    {
        // Gunakan API WhatsApp (Fonnte, Wablas, atau API lainnya)
        // Contoh menggunakan Fonnte
        $this->apiUrl = env('WHATSAPP_API_URL', 'https://api.fonnte.com/send');
        $this->apiKey = env('WHATSAPP_API_KEY', '');
    }
    
    /**
     * Kirim pesan WhatsApp
     */
    public function sendMessage($phoneNumber, $message)
    {
        // Format nomor telepon (hapus 0 di depan, tambah 62)
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->post($this->apiUrl, [
                'target' => $phoneNumber,
                'message' => $message,
            ]);
            
            return [
                'success' => $response->successful(),
                'response' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp send error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Kirim notifikasi pengiriman barang
     */
    public function sendPengirimanNotification($peminjaman)
    {
        $message = $this->formatPengirimanMessage($peminjaman);
        return $this->sendMessage($peminjaman->customer_whatsapp ?? $peminjaman->no_telepon, $message);
    }
    
    /**
     * Kirim pengingat pengembalian barang
     */
    public function sendPengingatPengembalian($peminjaman)
    {
        $message = $this->formatPengingatMessage($peminjaman);
        return $this->sendMessage($peminjaman->customer_whatsapp ?? $peminjaman->no_telepon, $message);
    }
    
    /**
     * Format pesan pengiriman
     */
    private function formatPengirimanMessage($peminjaman)
    {
        $items = '';
        foreach ($peminjaman->details as $detail) {
            $items .= "• {$detail->nama_barang} ({$detail->jumlah} unit)\n";
        }
        
        return "*MULTIDAYA INTI PERSADA*\n\n" .
               "Halo *{$peminjaman->nama_penyewa}*,\n\n" .
               "Barang yang Anda sewa telah dikirimkan. Berikut detail peminjaman Anda:\n\n" .
               "*INVOICE:* {$peminjaman->invoice_number}\n" .
               "*TANGGAL SEWA:* " . date('d/m/Y', strtotime($peminjaman->tanggal_sewa)) . "\n" .
               "*TANGGAL KEMBALI:* " . date('d/m/Y', strtotime($peminjaman->tanggal_kembali)) . "\n" .
               "*BARANG DISEWA:* \n{$items}\n" .
               "*TOTAL BIAYA:* Rp " . number_format($peminjaman->grand_total, 0, ',', '.') . "\n\n" .
               "Terima kasih telah menggunakan jasa kami. Jangan lupa untuk mengembalikan barang tepat waktu.\n\n" .
               "Jika ada pertanyaan, silakan hubungi kami.\n\n" .
               "*Salam,*\n" .
               "Multidaya Inti Persada";
    }
    
    /**
     * Format pesan pengingat pengembalian
     */
    private function formatPengingatMessage($peminjaman)
    {
        $tanggalKembali = date('d/m/Y', strtotime($peminjaman->tanggal_kembali));
        $sisaHari = $this->getDaysRemaining($peminjaman->tanggal_kembali);
        
        $peringatan = $sisaHari <= 0 ? "HARI INI adalah batas akhir pengembalian!" : "Tersisa {$sisaHari} hari lagi.";
        
        return "*MULTIDAYA INTI PERSADA*\n\n" .
               "Halo *{$peminjaman->nama_penyewa}*,\n\n" .
               "Ini adalah pengingat pengembalian barang.\n\n" .
               "*INVOICE:* {$peminjaman->invoice_number}\n" .
               "*TANGGAL KEMBALI:* {$tanggalKembali}\n" .
               "*STATUS:* {$peringatan}\n\n" .
               "Harap segera mengembalikan barang tepat waktu untuk menghindari denda keterlambatan.\n\n" .
               "Jika sudah mengembalikan, abaikan pesan ini.\n\n" .
               "*Salam,*\n" .
               "Multidaya Inti Persada";
    }
    
    /**
     * Format nomor telepon
     */
    private function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 2) == '62') {
            return $phone;
        }
        
        if (substr($phone, 0, 1) == '0') {
            return '62' . substr($phone, 1);
        }
        
        return '62' . $phone;
    }
    
    /**
     * Hitung sisa hari
     */
    private function getDaysRemaining($tanggalKembali)
    {
        $today = new \DateTime();
        $returnDate = new \DateTime($tanggalKembali);
        $interval = $today->diff($returnDate);
        
        if ($today > $returnDate) {
            return -$interval->days;
        }
        
        return $interval->days;
    }
}