<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'jenis',
        'harga_sewa',
        'stok',
        'tersedia',
        'disewa',
        'deskripsi',
        'gambar',
        'status',
        'created_by'
    ];

    protected $casts = [
        'harga_sewa' => 'decimal:2',
        'stok' => 'integer',
        'tersedia' => 'integer',
        'disewa' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor for formatted price
    public function getHargaSewaFormattedAttribute()
    {
        return 'Rp ' . number_format((float)$this->harga_sewa, 0, ',', '.');
    }

    // Generate kode barang otomatis
    public static function generateKodeBarang()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->kode_barang, 2)) + 1 : 1;
        return 'BS' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
}