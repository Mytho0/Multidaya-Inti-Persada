<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    
    protected $fillable = [
        'nama',
        'no_telepon',
        'email',
        'alamat',
        'tipe',
        'npwp',
        'total_transaksi',
        'total_nilai_transaksi',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'total_nilai_transaksi' => 'decimal:2'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}