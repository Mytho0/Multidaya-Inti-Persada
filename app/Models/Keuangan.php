<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';
    
    protected $fillable = [
        'peminjaman_id',
        'barang_id',
        'jenis',
        'sumber',
        'kategori',
        'deskripsi',
        'jumlah',
        'tanggal',
        'referensi',
        'sumber_dana',
        'bukti',
        'status',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePendapatan($query)
    {
        return $query->where('jenis', 'pendapatan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }
    
    public function scopeDariSewa($query)
    {
        return $query->where('sumber', 'sewa');
    }
    
    public function scopeDariOperasional($query)
    {
        return $query->where('sumber', 'operasional');
    }
    
    public function scopeDariPromosi($query)
    {
        return $query->where('sumber', 'promosi');
    }
    
    public function scopeDariInventaris($query)
    {
        return $query->where('sumber', 'inventaris');
    }
}