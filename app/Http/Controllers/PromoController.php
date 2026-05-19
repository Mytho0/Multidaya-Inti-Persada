<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Barang;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromoController extends Controller
{
    // Simpan promo baru dari modal konfirmasi
    public function store(Request $request)
    {
        $request->validate([
            'barang_id'      => 'required|exists:barang,id',
            'nama_promo'     => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'jenis_diskon'   => 'required|in:persen,nominal',
            'nilai_diskon'   => 'required|numeric|min:0',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'required|date|after:tanggal_mulai',
        ]);

        // Nonaktifkan promo lama untuk barang yang sama
        Promo::where('barang_id', $request->barang_id)
            ->where('status', 'aktif')
            ->update(['status' => 'nonaktif']);

        $promo = Promo::create([
            'barang_id'       => $request->barang_id,
            'nama_promo'      => $request->nama_promo,
            'deskripsi'       => $request->deskripsi,
            'jenis_diskon'    => $request->jenis_diskon,
            'nilai_diskon'    => $request->nilai_diskon,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'utilisasi_target'=> 70.00,
            'status'          => 'aktif',
            'sumber'          => 'ml',
        ]);

        // Update status recommendation jadi approved
        Recommendation::where('barang_id', $request->barang_id)
            ->where('source', 'ml')
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => "Promo {$promo->nama_promo} berhasil diterapkan!",
            'data'    => $promo
        ]);
    }

    // Cek dan update status promo otomatis
    public function checkAndUpdateStatus()
    {
        // 1. Expired kalau sudah lewat tanggal selesai
        Promo::where('status', 'aktif')
            ->where('tanggal_selesai', '<', Carbon::today())
            ->update(['status' => 'expired']);

        // 2. Nonaktif kalau utilisasi sudah tinggi (> utilisasi_target)
        $promosAktif = Promo::where('status', 'aktif')->with('barang')->get();

        foreach ($promosAktif as $promo) {
            if (!$promo->barang) continue;

            $stok     = max($promo->barang->stok, 1);
            $utilisasi = ($promo->barang->disewa / $stok) * 100;

            if ($utilisasi >= $promo->utilisasi_target) {
                $promo->update(['status' => 'nonaktif']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status promo berhasil diperbarui'
        ]);
    }

    // Ambil promo aktif untuk barang tertentu (dipanggil saat buat transaksi)
    public function getPromoAktif(int $barangId)
    {
        $promo = Promo::aktifUntukBarang($barangId)->first();

        if (!$promo) {
            return response()->json(['success' => false, 'promo' => null]);
        }

        $barang       = Barang::find($barangId);
        $hargaAsli    = $barang ? $barang->harga_sewa : 0;
        $hargaDiskon  = $promo->hitungHargaDiskon($hargaAsli);

        return response()->json([
            'success'      => true,
            'promo'        => [
                'id'           => $promo->id,
                'nama_promo'   => $promo->nama_promo,
                'jenis_diskon' => $promo->jenis_diskon,
                'nilai_diskon' => $promo->nilai_diskon,
                'harga_asli'   => $hargaAsli,
                'harga_diskon' => $hargaDiskon,
                'hemat'        => $hargaAsli - $hargaDiskon,
                'berlaku_hingga' => $promo->tanggal_selesai->format('d/m/Y'),
            ]
        ]);
    }

    // List semua promo (untuk halaman manajemen promo)
    public function index()
    {
        $promos = Promo::with('barang')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'id'             => $p->id,
                    'nama_promo'     => $p->nama_promo,
                    'nama_barang'    => $p->barang->nama_barang ?? '-',
                    'jenis_diskon'   => $p->jenis_diskon,
                    'nilai_diskon'   => $p->nilai_diskon,
                    'tanggal_mulai'  => $p->tanggal_mulai->format('d/m/Y'),
                    'tanggal_selesai'=> $p->tanggal_selesai->format('d/m/Y'),
                    'status'         => $p->status,
                    'sumber'         => $p->sumber,
                ];
            });

        return response()->json(['success' => true, 'data' => $promos]);
    }
}
