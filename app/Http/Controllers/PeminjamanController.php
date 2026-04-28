<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with('details');

        // Filter status
        if ($request->status == 'aktif') {
            $query->where('status_pengembalian', 'aktif');
        } elseif ($request->status == 'riwayat' || $request->status == 'selesai') {
            $query->where('status_pengembalian', 'selesai');
        } elseif ($request->status == 'terlambat') {
            $query->where('status_pengembalian', 'terlambat');
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_penyewa', 'like', '%' . $request->search . '%')
                    ->orWhere('no_telepon', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        if ($request->sort == 'name_asc') {
            $query->orderBy('nama_penyewa', 'asc');
        } elseif ($request->sort == 'name_desc') {
            $query->orderBy('nama_penyewa', 'desc');
        } elseif ($request->sort == 'date_asc') {
            $query->orderBy('tanggal_sewa', 'asc');
        } elseif ($request->sort == 'date_desc') {
            $query->orderBy('tanggal_sewa', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $peminjaman = $query->paginate(10);

        // Get available barang for form
        $barang = Barang::where('status', 'aktif')->where('tersedia', '>', 0)->get();

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'data' => $peminjaman->items(),
                'pagination' => [
                    'current_page' => $peminjaman->currentPage(),
                    'last_page' => $peminjaman->lastPage(),
                    'per_page' => $peminjaman->perPage(),
                    'total' => $peminjaman->total(),
                    'from' => $peminjaman->firstItem(),
                    'to' => $peminjaman->lastItem()
                ]
            ]);
        }

        return view('peminjaman.index', compact('peminjaman', 'barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penyewa' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'customer_whatsapp' => 'nullable|string|max:15',
            'nama_acara' => 'nullable|string|max:255',
            'lokasi_acara' => 'nullable|string',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_sewa',
            'waktu_sewa' => 'required',
            'waktu_kembali' => 'required',
            'barang' => 'required|array|min:1',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $details = [];

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id']);
                if (!$barang) {
                    throw new \Exception('Barang tidak ditemukan');
                }

                // Cek ketersediaan stok
                if ($barang->tersedia < $item['jumlah']) {
                    throw new \Exception('Stok barang ' . $barang->nama_barang . ' tidak mencukupi (Tersedia: ' . $barang->tersedia . ')');
                }

                $subtotal = $barang->harga_sewa * $item['jumlah'];
                $totalHarga += $subtotal;

                $details[] = [
                    'barang_id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'jenis_barang' => $barang->jenis,
                    'harga_sewa' => $barang->harga_sewa,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal
                ];

                // Update stock
                $barang->decrement('tersedia', $item['jumlah']);
                $barang->increment('disewa', $item['jumlah']);
            }

            $grandTotal = $totalHarga - ($request->diskon ?? 0);

            $peminjaman = Peminjaman::create([
                'invoice_number' => Peminjaman::generateInvoiceNumber(),
                'nama_penyewa' => $request->nama_penyewa,
                'no_telepon' => $request->no_telepon,
                'customer_whatsapp' => $request->customer_whatsapp ?? $request->no_telepon,
                'nama_acara' => $request->nama_acara,
                'lokasi_acara' => $request->lokasi_acara,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_kembali' => $request->tanggal_kembali,
                'waktu_sewa' => $request->waktu_sewa,
                'waktu_kembali' => $request->waktu_kembali,
                'status_pembayaran' => $request->status_pembayaran ?? 'belum_bayar',
                'status_pengembalian' => 'aktif',
                'total_harga' => $totalHarga,
                'diskon' => $request->diskon ?? 0,
                'grand_total' => $grandTotal,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id()
            ]);

            foreach ($details as $detail) {
                $detail['peminjaman_id'] = $peminjaman->id;
                DetailPeminjaman::create($detail);
            }

            DB::commit();

            // Kirim notifikasi pengiriman secara otomatis
            try {
                $this->whatsappService->sendPengirimanNotification($peminjaman);
                $peminjaman->update(['whatsapp_sent_pengiriman' => true]);
            } catch (\Exception $e) {
                Log::error('Auto WhatsApp notification failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil ditambahkan',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with('details')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }

    /**
     * Process pengembalian barang.
     */
    public function pengembalian(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'foto_pengembalian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kondisi_barang' => 'required|in:baik,kurang_baik,rusak',
            'kerusakan' => 'nullable|string',
            'biaya_kerusakan' => 'nullable|numeric|min:0',
            'catatan_pengembalian' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Hitung denda keterlambatan
            $tanggalKembali = new \DateTime($peminjaman->tanggal_kembali);
            $today = new \DateTime();
            $selisihHari = $today > $tanggalKembali ? $today->diff($tanggalKembali)->days : 0;
            $dendaKeterlambatan = $selisihHari * 50000;

            $totalDenda = $dendaKeterlambatan + ($request->biaya_kerusakan ?? 0);

            $updateData = [
                'status_pengembalian' => 'selesai',
                'tanggal_pengembalian_real' => now(),
                'kondisi_barang' => $request->kondisi_barang,
                'kerusakan' => $request->kerusakan,
                'biaya_kerusakan' => $request->biaya_kerusakan ?? 0,
                'denda' => $totalDenda,
                'catatan_pengembalian' => $request->catatan_pengembalian
            ];

            if ($request->hasFile('foto_pengembalian')) {
                $path = $request->file('foto_pengembalian')->store('pengembalian', 'public');
                $updateData['foto_pengembalian'] = $path;
            }

            $peminjaman->update($updateData);

            // Update stok barang (kembalikan ke tersedia)
            foreach ($peminjaman->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->increment('tersedia', $detail->jumlah);
                    $barang->decrement('disewa', $detail->jumlah);
                }
            }

            DB::commit();

            $message = 'Pengembalian barang berhasil';
            if ($totalDenda > 0) {
                $message .= ' dengan denda Rp ' . number_format($totalDenda, 0, ',', '.');
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload bukti pembayaran.
     */
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                $peminjaman->update([
                    'bukti_pembayaran' => $path,
                    'status_pembayaran' => 'dp'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate invoice PDF.
     */
    public function generateInvoice($id)
    {
        try {
            $peminjaman = Peminjaman::with('details')->findOrFail($id);

            $data = [
                'peminjaman' => $peminjaman,
                'company' => [
                    'name' => 'CV. Multidaya Inti Persada',
                    'address' => 'Jin Rayong, Berdikari No. 17, Kedon Jeruk, Jakarta Barat 11540',
                    'phone' => '08123456789',
                    'email' => 'info@multidaya.com'
                ]
            ];

            $pdf = Pdf::loadView('peminjaman.invoice', $data);
            $pdf->setPaper('a4', 'portrait');

            // Gunakan ID sebagai nama file (aman tanpa karakter khusus)
            $filename = 'invoice_' . $peminjaman->id . '_' . date('Ymd') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate invoice: ' . $e->getMessage()
            ], 500);
        }
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Cek apakah peminjaman sudah selesai
        if ($peminjaman->status_pengembalian == 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman yang sudah selesai tidak dapat diedit'
            ], 422);
        }

        $request->validate([
            'nama_penyewa' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'customer_whatsapp' => 'nullable|string|max:15',
            'nama_acara' => 'nullable|string|max:255',
            'lokasi_acara' => 'nullable|string',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_sewa',
            'waktu_sewa' => 'required',
            'waktu_kembali' => 'required',
            'barang' => 'required|array|min:1',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            // 1. Kembalikan stok barang lama
            foreach ($peminjaman->details as $detail) {
                $barangLama = Barang::find($detail->barang_id);
                if ($barangLama) {
                    $barangLama->increment('tersedia', $detail->jumlah);
                    $barangLama->decrement('disewa', $detail->jumlah);
                }
            }

            // 2. Hapus detail lama
            DetailPeminjaman::where('peminjaman_id', $peminjaman->id)->delete();

            // 3. Hitung total harga baru
            $totalHarga = 0;
            $details = [];

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id']);
                if (!$barang) {
                    throw new \Exception('Barang tidak ditemukan');
                }

                // Cek ketersediaan stok baru
                if ($barang->tersedia < $item['jumlah']) {
                    throw new \Exception('Stok barang ' . $barang->nama_barang . ' tidak mencukupi (Tersedia: ' . $barang->tersedia . ')');
                }

                $subtotal = $barang->harga_sewa * $item['jumlah'];
                $totalHarga += $subtotal;

                $details[] = [
                    'barang_id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'jenis_barang' => $barang->jenis,
                    'harga_sewa' => $barang->harga_sewa,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal
                ];

                // Update stock baru
                $barang->decrement('tersedia', $item['jumlah']);
                $barang->increment('disewa', $item['jumlah']);
            }

            $grandTotal = $totalHarga - ($request->diskon ?? 0);

            // 4. Update peminjaman
            $peminjaman->update([
                'nama_penyewa' => $request->nama_penyewa,
                'no_telepon' => $request->no_telepon,
                'customer_whatsapp' => $request->customer_whatsapp ?? $request->no_telepon,
                'nama_acara' => $request->nama_acara,
                'lokasi_acara' => $request->lokasi_acara,
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_kembali' => $request->tanggal_kembali,
                'waktu_sewa' => $request->waktu_sewa,
                'waktu_kembali' => $request->waktu_kembali,
                'status_pembayaran' => $request->status_pembayaran ?? $peminjaman->status_pembayaran,
                'total_harga' => $totalHarga,
                'diskon' => $request->diskon ?? 0,
                'grand_total' => $grandTotal,
                'keterangan' => $request->keterangan
            ]);

            // 5. Tambah detail baru
            foreach ($details as $detail) {
                $detail['peminjaman_id'] = $peminjaman->id;
                DetailPeminjaman::create($detail);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diupdate',
                'data' => $peminjaman->load('details')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek pelanggan berdasarkan nama atau nomor telepon
     */
    public function cekPelanggan(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:2'
        ]);

        $keyword = $request->keyword;

        // Cari pelanggan berdasarkan nama atau no telepon
        $pelanggan = Pelanggan::where('nama', 'like', "%{$keyword}%")
            ->orWhere('no_telepon', 'like', "%{$keyword}%")
            ->first();

        if ($pelanggan) {
            // Ambil riwayat peminjaman
            $riwayat = $pelanggan->peminjaman()
                ->with('details')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'exists' => true,
                'data' => $pelanggan,
                'riwayat' => $riwayat,
                'total_transaksi' => $pelanggan->total_transaksi,
                'total_nilai' => $pelanggan->total_nilai_transaksi
            ]);
        }

        // Cari pelanggan yang mirip (suggestions)
        $suggestions = Pelanggan::where('nama', 'like', "%{$keyword}%")
            ->orWhere('no_telepon', 'like', "%{$keyword}%")
            ->limit(5)
            ->get(['id', 'nama', 'no_telepon']);

        return response()->json([
            'exists' => false,
            'message' => 'Pelanggan tidak ditemukan',
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Get all pelanggan (untuk autocomplete)
     */
    public function getPelangganList(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->search) {
            $query->where('nama', 'like', "%{$request->search}%")
                ->orWhere('no_telepon', 'like', "%{$request->search}%");
        }

        $pelanggan = $query->orderBy('nama')->limit(20)->get(['id', 'nama', 'no_telepon', 'alamat', 'email']);

        return response()->json([
            'success' => true,
            'data' => $pelanggan
        ]);
    }

    /**
     * Kirim notifikasi pengiriman ke pelanggan
     */
    public function sendPengirimanNotification($id)
    {
        $peminjaman = Peminjaman::with('details')->findOrFail($id);

        if ($peminjaman->whatsapp_sent_pengiriman) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi pengiriman sudah pernah dikirim'
            ]);
        }

        $result = $this->whatsappService->sendPengirimanNotification($peminjaman);

        if ($result['success']) {
            $peminjaman->update([
                'whatsapp_sent_pengiriman' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi pengiriman berhasil dikirim'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim notifikasi: ' . ($result['message'] ?? 'Unknown error')
        ]);
    }

    /**
     * Kirim pengingat pengembalian manual
     */
    public function sendPengingatPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $result = $this->whatsappService->sendPengingatPengembalian($peminjaman);

        if ($result['success']) {
            $peminjaman->update([
                'whatsapp_sent_pengingat' => true,
                'whatsapp_pengingat_sent_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengingat pengembalian berhasil dikirim'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim pengingat: ' . ($result['message'] ?? 'Unknown error')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        DB::beginTransaction();
        try {
            // Restore stock jika status masih aktif
            if ($peminjaman->status_pengembalian == 'aktif') {
                foreach ($peminjaman->details as $detail) {
                    $barang = Barang::find($detail->barang_id);
                    if ($barang) {
                        $barang->increment('tersedia', $detail->jumlah);
                        $barang->decrement('disewa', $detail->jumlah);
                    }
                }
            }

            $peminjaman->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }
}
