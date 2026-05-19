<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    protected $fillable = [
        'barang_id', 'nama_promo', 'deskripsi',
        'jenis_diskon', 'nilai_diskon',
        'tanggal_mulai', 'tanggal_selesai',
        'utilisasi_target', 'status', 'sumber'
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'nilai_diskon'     => 'float',
        'utilisasi_target' => 'float',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Cek apakah promo masih aktif
    public function isAktif(): bool
    {
        return $this->status === 'aktif'
            && Carbon::today()->between($this->tanggal_mulai, $this->tanggal_selesai);
    }

    // Hitung harga setelah diskon
    public function hitungHargaDiskon(float $hargaAsli): float
    {
        if (!$this->isAktif()) return $hargaAsli;

        if ($this->jenis_diskon === 'persen') {
            return $hargaAsli - ($hargaAsli * $this->nilai_diskon / 100);
        }
        return max(0, $hargaAsli - $this->nilai_diskon);
    }

    // Scope: promo aktif untuk barang tertentu
    public function scopeAktifUntukBarang($query, int $barangId)
    {
        return $query->where('barang_id', $barangId)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::today())
            ->where('tanggal_selesai', '>=', Carbon::today());
    }
}
