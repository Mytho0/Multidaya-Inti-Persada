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
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['details', 'pelanggan']);

        // Filter status
        if ($request->status == 'aktif') {
            $query->where('status_pengembalian', 'aktif');
        } elseif ($request->status == 'riwayat' || $request->status == 'selesai') {
            $query->where('status_pengembalian', 'selesai');
        } elseif ($request->status == 'terlambat') {
            $query->where('status_pengembalian', 'terlambat');
        }

        // Filter pelanggan baru/lama
        if ($request->pelanggan == 'new') {
            $query->whereHas('pelanggan', function ($q) {
                $q->where('total_transaksi', '<=', 1);
            });
        } elseif ($request->pelanggan == 'old') {
            $query->whereHas('pelanggan', function ($q) {
                $q->where('total_transaksi', '>', 1);
            });
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
        $barang = Barang::where('status', 'aktif')->where('tersedia', '>', 0)->get();

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
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'tipe_pelanggan' => 'nullable|in:perorangan,perusahaan',
            'nama_acara' => 'nullable|string|max:255',
            'lokasi_acara' => 'nullable|string',
            'tanggal_sewa' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_sewa',
            'waktu_sewa' => 'required',
            'waktu_kembali' => 'required',
            'barang' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Decode barang dari JSON
            $barangArray = json_decode($request->barang, true);

            if (!$barangArray || !is_array($barangArray) || count($barangArray) === 0) {
                throw new \Exception('Data barang tidak valid');
            }

            $pelanggan = null;
            $isNewCustomer = false;

            if ($request->pelanggan_id) {
                $pelanggan = Pelanggan::find($request->pelanggan_id);
            } elseif ($request->no_telepon) {
                $pelanggan = Pelanggan::where('no_telepon', $request->no_telepon)->first();
            }

            if (!$pelanggan) {
                $pelanggan = Pelanggan::create([
                    'nama' => $request->nama_penyewa,
                    'no_telepon' => $request->no_telepon,
                    'email' => $request->email ?? null,
                    'alamat' => $request->alamat ?? null,
                    'tipe' => $request->tipe_pelanggan ?? 'perorangan',
                    'status' => 'aktif'
                ]);
                $isNewCustomer = true;
            }

            $totalHarga = 0;
            $details = [];

            foreach ($barangArray as $item) {
                $barang = Barang::find($item['id']);
                if (!$barang) {
                    throw new \Exception('Barang tidak ditemukan');
                }

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

                $barang->decrement('tersedia', $item['jumlah']);
                $barang->increment('disewa', $item['jumlah']);
            }

            $grandTotal = $totalHarga - ($request->diskon ?? 0);

            // Hitung PPN 11%
            $ppn = 0.11;
            $totalPpn = $grandTotal * $ppn;
            $grandTotalWithPpn = $grandTotal + $totalPpn;

            // Hitung Jatuh Tempo (tanggal sewa + 7 hari)
            $jatuhTempo = date('Y-m-d', strtotime($request->tanggal_sewa . ' +7 days'));

            $peminjaman = Peminjaman::create([
                'invoice_number' => Peminjaman::generateInvoiceNumber(),
                'pelanggan_id' => $pelanggan->id,
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
                'ppn' => $ppn,
                'total_ppn' => $totalPpn,
                'grand_total_with_ppn' => $grandTotalWithPpn,
                'jatuh_tempo_pembayaran' => $jatuhTempo,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id()
            ]);

            // Upload bukti pembayaran jika ada
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                $peminjaman->update(['bukti_pembayaran' => $path]);
            }

            $pelanggan->increment('total_transaksi');
            $pelanggan->increment('total_nilai_transaksi', $grandTotalWithPpn);

            foreach ($details as $detail) {
                $detail['peminjaman_id'] = $peminjaman->id;
                DetailPeminjaman::create($detail);
            }

            DB::commit();

            // Kirim notifikasi WhatsApp
            try {
                $this->whatsappService->sendPengirimanNotification($peminjaman);
                $peminjaman->update(['whatsapp_sent_pengiriman' => true]);
            } catch (\Exception $e) {
                Log::error('Auto WhatsApp notification failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => $isNewCustomer ? 'Peminjaman berhasil ditambahkan (Pelanggan baru)' : 'Peminjaman berhasil ditambahkan',
                'data' => $peminjaman,
                'is_new_customer' => $isNewCustomer
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
    public function show(string $id)
    {
        $peminjaman = Peminjaman::with(['details', 'pelanggan'])->findOrFail($id);

        // Format tanggal untuk frontend
        $data = $peminjaman->toArray();
        $data['tanggal_sewa'] = date('Y-m-d', strtotime($peminjaman->tanggal_sewa));
        $data['tanggal_kembali'] = date('Y-m-d', strtotime($peminjaman->tanggal_kembali));

        // Pastikan field baru tersedia
        $data['ppn'] = $peminjaman->ppn ?? 0.11;
        $data['total_ppn'] = $peminjaman->total_ppn ?? 0;
        $data['grand_total_with_ppn'] = $peminjaman->grand_total_with_ppn ?? $peminjaman->grand_total;
        $data['jatuh_tempo_pembayaran'] = $peminjaman->jatuh_tempo_pembayaran;

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Process pengembalian barang.
     * FIXED: Menghapus kolom 'kerusakan' yang tidak ada di database
     */
    public function pengembalian(Request $request, string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'foto_pengembalian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kondisi_barang' => 'required|in:baik,kurang_baik,rusak',
            'biaya_kerusakan' => 'nullable|numeric|min:0',
            'catatan_pengembalian' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $tanggalKembali = new \DateTime($peminjaman->tanggal_kembali);
            $today = new \DateTime();
            $selisihHari = $today > $tanggalKembali ? $today->diff($tanggalKembali)->days : 0;
            $dendaKeterlambatan = $selisihHari * 50000;
            $totalDenda = $dendaKeterlambatan + ($request->biaya_kerusakan ?? 0);

            $updateData = [
                'status_pengembalian' => 'selesai',
                'tanggal_pengembalian_real' => now(),
                'kondisi_barang' => $request->kondisi_barang,
                'biaya_kerusakan' => $request->biaya_kerusakan ?? 0,
                'denda' => $totalDenda,
                'catatan_pengembalian' => $request->catatan_pengembalian
            ];

            if ($request->hasFile('foto_pengembalian')) {
                $path = $request->file('foto_pengembalian')->store('pengembalian', 'public');
                $updateData['foto_pengembalian'] = $path;
            }

            $peminjaman->update($updateData);

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
    public function uploadBukti(Request $request, string $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        DB::beginTransaction();
        try {
            $path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                // Hapus file lama jika ada
                if ($peminjaman->bukti_pembayaran && file_exists(storage_path('app/public/' . $peminjaman->bukti_pembayaran))) {
                    unlink(storage_path('app/public/' . $peminjaman->bukti_pembayaran));
                }

                $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                $peminjaman->update([
                    'bukti_pembayaran' => $path,
                    'status_pembayaran' => 'dp'
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload',
                'bukti_pembayaran_url' => asset("storage/{$path}")
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
    public function generateInvoice(string $id)
    {
        try {
            $peminjaman = Peminjaman::with(['details', 'pelanggan'])->findOrFail($id);
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
            $filename = 'invoice_' . $peminjaman->id . '_' . date('Ymd') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

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
            // Kembalikan stok barang lama
            foreach ($peminjaman->details as $detail) {
                $barangLama = Barang::find($detail->barang_id);
                if ($barangLama) {
                    $barangLama->increment('tersedia', $detail->jumlah);
                    $barangLama->decrement('disewa', $detail->jumlah);
                }
            }

            // Hapus detail lama
            DetailPeminjaman::where('peminjaman_id', $peminjaman->id)->delete();

            $totalHarga = 0;
            $details = [];

            foreach ($request->barang as $item) {
                $barang = Barang::find($item['id']);
                if (!$barang) {
                    throw new \Exception('Barang tidak ditemukan');
                }

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

                $barang->decrement('tersedia', $item['jumlah']);
                $barang->increment('disewa', $item['jumlah']);
            }

            $grandTotal = $totalHarga - ($request->diskon ?? 0);

            // Hitung PPN 11%
            $ppn = 0.11;
            $totalPpn = $grandTotal * $ppn;
            $grandTotalWithPpn = $grandTotal + $totalPpn;

            // Hitung Jatuh Tempo
            $jatuhTempo = date('Y-m-d', strtotime($request->tanggal_sewa . ' +7 days'));

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
                'ppn' => $ppn,
                'total_ppn' => $totalPpn,
                'grand_total_with_ppn' => $grandTotalWithPpn,
                'jatuh_tempo_pembayaran' => $jatuhTempo,
                'keterangan' => $request->keterangan
            ]);

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
        $pelanggan = Pelanggan::where('nama', 'like', "%{$keyword}%")
            ->orWhere('no_telepon', 'like', "%{$keyword}%")
            ->first();

        if ($pelanggan) {
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
     * Get all pelanggan for autocomplete
     */
    public function getPelangganList(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->search && strlen($request->search) >= 2) {
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
    public function sendPengirimanNotification(string $id)
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
            $peminjaman->update(['whatsapp_sent_pengiriman' => true]);
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
    public function sendPengingatPengembalian(string $id)
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
    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        DB::beginTransaction();
        try {
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

    // ==================== METHODS UNTUK DASHBOARD ====================

    /**
     * Get calendar events for dashboard
     */
    public function getCalendarEvents()
    {
        $events = [];
        $peminjaman = Peminjaman::with('details')->get();

        foreach ($peminjaman as $rental) {
            // Rental start event
            if ($rental->tanggal_sewa) {
                $events[] = [
                    'date' => Carbon::parse($rental->tanggal_sewa)->format('Y-m-d'),
                    'title' => 'Sewa: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'rental_start',
                    'customer' => $rental->nama_penyewa,
                    'invoice' => $rental->invoice_number,
                    'color' => 'green'
                ];
            }

            // Due date event (tanggal_kembali)
            if ($rental->tanggal_kembali) {
                $events[] = [
                    'date' => Carbon::parse($rental->tanggal_kembali)->format('Y-m-d'),
                    'title' => 'Jatuh Tempo: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'due_date',
                    'customer' => $rental->nama_penyewa,
                    'invoice' => $rental->invoice_number,
                    'color' => 'orange'
                ];
            }

            // Actual return date event
            if ($rental->tanggal_pengembalian_real) {
                $events[] = [
                    'date' => Carbon::parse($rental->tanggal_pengembalian_real)->format('Y-m-d'),
                    'title' => 'Dikembalikan: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'returned',
                    'customer' => $rental->nama_penyewa,
                    'invoice' => $rental->invoice_number,
                    'color' => 'blue'
                ];
            }

            // Payment due date
            if ($rental->jatuh_tempo_pembayaran && $rental->status_pembayaran != 'lunas') {
                $events[] = [
                    'date' => Carbon::parse($rental->jatuh_tempo_pembayaran)->format('Y-m-d'),
                    'title' => 'Pembayaran: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'payment_due',
                    'customer' => $rental->nama_penyewa,
                    'invoice' => $rental->invoice_number,
                    'color' => 'red'
                ];
            }
        }

        // Sort events by date
        usort($events, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get reminders for dashboard (overdue returns and payment due)
     */
    public function getReminders()
    {
        $reminders = [];
        $today = Carbon::today();

        // Overdue returns (tanggal_kembali < today and status masih aktif)
        $overdueReturns = Peminjaman::where('status_pengembalian', 'aktif')
            ->whereDate('tanggal_kembali', '<', $today)
            ->get();

        foreach ($overdueReturns as $rental) {
            $dueDate = Carbon::parse($rental->tanggal_kembali);
            $daysOverdue = $dueDate->diffInDays($today);

            $reminders[] = [
                'id' => $rental->id,
                'title' => 'Pengembalian Terlambat',
                'description' => $rental->invoice_number . ' - ' . $rental->nama_penyewa .
                    ' terlambat ' . $daysOverdue . ' hari',
                'due_date' => $dueDate->format('d/m/Y'),
                'is_overdue' => true,
                'type' => 'return_overdue',
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa
            ];
        }

        // Due today returns
        $dueToday = Peminjaman::where('status_pengembalian', 'aktif')
            ->whereDate('tanggal_kembali', '=', $today)
            ->get();

        foreach ($dueToday as $rental) {
            $reminders[] = [
                'id' => $rental->id,
                'title' => 'Jatuh Tempo Hari Ini',
                'description' => $rental->invoice_number . ' - ' . $rental->nama_penyewa .
                    ' harus dikembalikan hari ini',
                'due_date' => Carbon::parse($rental->tanggal_kembali)->format('d/m/Y'),
                'is_overdue' => false,
                'type' => 'return_due',
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa
            ];
        }

        // Payment overdue
        $paymentOverdue = Peminjaman::where('status_pembayaran', '!=', 'lunas')
            ->where('status_pembayaran', '!=', 'paid')
            ->whereDate('jatuh_tempo_pembayaran', '<', $today)
            ->get();

        foreach ($paymentOverdue as $rental) {
            $dueDate = Carbon::parse($rental->jatuh_tempo_pembayaran);
            $daysOverdue = $dueDate->diffInDays($today);

            $reminders[] = [
                'id' => $rental->id,
                'title' => 'Pembayaran Terlambat',
                'description' => $rental->invoice_number . ' - ' . $rental->nama_penyewa .
                    ' pembayaran terlambat ' . $daysOverdue . ' hari',
                'due_date' => $dueDate->format('d/m/Y'),
                'is_overdue' => true,
                'type' => 'payment_overdue',
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa
            ];
        }

        // Payment due today
        $paymentDueToday = Peminjaman::where('status_pembayaran', '!=', 'lunas')
            ->where('status_pembayaran', '!=', 'paid')
            ->whereDate('jatuh_tempo_pembayaran', '=', $today)
            ->get();

        foreach ($paymentDueToday as $rental) {
            $reminders[] = [
                'id' => $rental->id,
                'title' => 'Pembayaran Jatuh Tempo',
                'description' => $rental->invoice_number . ' - ' . $rental->nama_penyewa .
                    ' pembayaran jatuh tempo hari ini',
                'due_date' => Carbon::parse($rental->jatuh_tempo_pembayaran)->format('d/m/Y'),
                'is_overdue' => false,
                'type' => 'payment_due',
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa
            ];
        }

        // Sort: overdue first, then by date
        usort($reminders, function ($a, $b) {
            if ($a['is_overdue'] != $b['is_overdue']) {
                return $b['is_overdue'] - $a['is_overdue'];
            }
            return strcmp($a['due_date'], $b['due_date']);
        });

        return response()->json([
            'success' => true,
            'data' => array_slice($reminders, 0, 10)
        ]);
    }

    /**
     * Get dashboard statistics for API
     */
    public function getDashboardStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Pendapatan bulan ini
        $pendapatanBulanIni = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $currentMonth)
            ->whereYear('tanggal_pengembalian_real', $currentYear)
            ->sum('grand_total_with_ppn');

        // Sewa aktif
        $sewaAktif = Peminjaman::where('status_pengembalian', 'aktif')->count();

        // Pendapatan hari ini
        $pendapatanHariIni = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereDate('tanggal_pengembalian_real', Carbon::today())
            ->sum('grand_total_with_ppn');

        // Jumlah transaksi bulan ini
        $totalTransaksi = Peminjaman::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'pendapatan_bulan_ini' => $pendapatanBulanIni,
                'sewa_aktif' => $sewaAktif,
                'pendapatan_hari_ini' => $pendapatanHariIni,
                'total_transaksi' => $totalTransaksi
            ]
        ]);
    }
}
