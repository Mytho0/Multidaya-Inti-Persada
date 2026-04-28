<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'invoice_number',
        'pelanggan_id',
        'nama_penyewa',
        'no_telepon',
        'customer_whatsapp',
        'nama_acara',
        'lokasi_acara',
        'tanggal_sewa',
        'tanggal_kembali',
        'waktu_sewa',
        'waktu_kembali',
        'status_pembayaran',
        'status_pengembalian',
        'total_harga',
        'diskon',
        'grand_total',
        'keterangan',
        'bukti_pembayaran',
        'foto_pengembalian',
        'catatan_pengembalian',
        'kondisi_barang',
        'kerusakan',
        'biaya_kerusakan',
        'denda',
        'tanggal_pengembalian_real',
        'whatsapp_sent_pengiriman',
        'whatsapp_sent_pengingat',
        'whatsapp_pengingat_sent_at',
        'created_by'
    ];

    protected $casts = [
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
        'waktu_sewa' => 'string',
        'waktu_kembali' => 'string',
        'tanggal_pengembalian_real' => 'datetime',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'biaya_kerusakan' => 'decimal:2',
        'denda' => 'decimal:2',
        'whatsapp_sent_pengiriman' => 'boolean',
        'whatsapp_sent_pengingat' => 'boolean',
        'whatsapp_pengingat_sent_at' => 'datetime'
    ];

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');

        $last = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->invoice_number, -4);
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }

        return 'INV/MIP/' . $year . '/' . $month . '/' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
