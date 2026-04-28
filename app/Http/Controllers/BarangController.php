<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        // Filter by jenis
        if ($request->jenis && $request->jenis != 'all') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('jenis', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('nama_barang', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_barang', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('harga_sewa', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga_sewa', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stok', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stok', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $barang = $query->paginate(10);

        // Get unique jenis for filter
        $jenisList = Barang::select('jenis')->distinct()->pluck('jenis')->toArray();

        if ($request->ajax()) {
            return response()->json([
                'data' => $barang->items(),
                'pagination' => [
                    'current_page' => $barang->currentPage(),
                    'last_page' => $barang->lastPage(),
                    'per_page' => $barang->perPage(),
                    'total' => $barang->total(),
                    'from' => $barang->firstItem(),
                    'to' => $barang->lastItem()
                ]
            ]);
        }

        return view('barang.index', compact('barang', 'jenisList'));
    }

    /**
     * Get all data for global statistics
     */
    public function getAllData()
    {
        $barang = Barang::all();

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    /**
     * Get statistics summary
     */
    public function getStats()
    {
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');
        $totalTersedia = Barang::sum('tersedia');
        $totalDisewa = Barang::sum('disewa');

        return response()->json([
            'success' => true,
            'data' => [
                'total_barang' => $totalBarang,
                'total_stok' => $totalStok,
                'total_tersedia' => $totalTersedia,
                'total_disewa' => $totalDisewa
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'harga_sewa' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tersedia' => 'required|integer|min:0',
            'disewa' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Validate stock consistency
        if ($request->tersedia + $request->disewa > $request->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tersedia + disewa tidak boleh melebihi stok'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'kode_barang' => Barang::generateKodeBarang(),
                'nama_barang' => $request->nama_barang,
                'jenis' => $request->jenis,
                'harga_sewa' => $request->harga_sewa,
                'stok' => $request->stok,
                'tersedia' => $request->tersedia,
                'disewa' => $request->disewa,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
                'created_by' => Auth::id()
            ];

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('barang', $filename, 'public');
                $data['gambar'] = $path;
            }

            $barang = Barang::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan barang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'harga_sewa' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'tersedia' => 'required|integer|min:0',
            'disewa' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Validate stock consistency
        if ($request->tersedia + $request->disewa > $request->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tersedia + disewa tidak boleh melebihi stok'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'nama_barang' => $request->nama_barang,
                'jenis' => $request->jenis,
                'harga_sewa' => $request->harga_sewa,
                'stok' => $request->stok,
                'tersedia' => $request->tersedia,
                'disewa' => $request->disewa,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ];

            // Handle upload gambar baru
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                    Storage::disk('public')->delete($barang->gambar);
                }

                $file = $request->file('gambar');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('barang', $filename, 'public');
                $data['gambar'] = $path;
            }

            $barang->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil diupdate',
                'data' => $barang
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate barang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        DB::beginTransaction();
        try {
            // Hapus gambar jika ada
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $barang->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus barang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStock(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'stok' => 'required|integer|min:0',
            'tersedia' => 'required|integer|min:0',
            'disewa' => 'required|integer|min:0'
        ]);

        if ($request->tersedia + $request->disewa > $request->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tersedia + disewa tidak boleh melebihi stok'
            ], 422);
        }

        $barang->update([
            'stok' => $request->stok,
            'tersedia' => $request->tersedia,
            'disewa' => $request->disewa
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok barang berhasil diupdate'
        ]);
    }
}
