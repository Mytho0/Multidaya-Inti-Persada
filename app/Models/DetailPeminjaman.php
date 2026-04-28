<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    
    protected $fillable = [
        'peminjaman_id',
        'barang_id',
        'nama_barang',
        'jenis_barang',
        'harga_sewa',
        'jumlah',
        'subtotal'
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}