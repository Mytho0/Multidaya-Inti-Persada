<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPendapatan extends Model
{
    protected $table = 'transaksi_pendapatans';

    protected $fillable = [
        'kode_transaksi',
        'sumber',
        'kategori',
        'deskripsi',
        'jumlah',
        'tanggal',
        'referensi',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:0'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper untuk generate kode transaksi
    public static function generateKode()
    {
        $last = self::latest('id')->first();
        $number = $last ? intval(substr($last->kode_transaksi, -4)) + 1 : 1;
        return 'TRX-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
