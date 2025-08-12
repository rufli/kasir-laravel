<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request  )
    {
       try {
            $query = Produk::with('kategoriProduk')->latest();

            // Tambahkan filter pencarian jika ada parameter search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            }

            $produks = $query->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Daftar Produk',
                'data' => $produks
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil daftar produk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Menyimpan produk baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'            => 'required|date',
            'nama'               => 'required|string|max:45',
            'harga'              => 'required|numeric|min:0|max:99999999.99', // Sama dengan web
            'stok'               => 'required|integer|min:0',
            'gambar'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_produk_id' => 'required|exists:kategori_produks,id', // Konsisten dengan web
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal!',
                'errors' => $validator->errors()
            ], 422); // 422 lebih tepat untuk validation error
        }

        try {
            $validated = $validator->validated();

            // Handle upload gambar - SAMA DENGAN WEB CONTROLLER
            if ($request->hasFile('gambar')) {
                $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
            }

            $produk = Produk::create($validated);
            $produk->load('kategoriProduk'); // Load relasi untuk response

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan!',
                'data' => $produk
            ], 201);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan produk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Menampilkan detail produk berdasarkan ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $produk = Produk::with('kategoriProduk')->find($id);

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan!'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail Produk',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil detail produk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail produk'
            ], 500);
        }
    }

    /**
     * Memperbarui data produk yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tanggal'            => 'required|date',
            'nama'               => 'required|string|max:45',
            'harga'              => 'required|numeric|min:0|max:99999999.99', // Sama dengan web
            'stok'               => 'required|integer|min:0',
            'gambar'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_produk_id' => 'required|exists:kategori_produks,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $validated = $validator->validated();

            // Handle upload gambar - SAMA DENGAN WEB CONTROLLER
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if ($produk->gambar) {
                    Storage::disk('public')->delete($produk->gambar);
                }
                // Simpan gambar baru
                $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
            }

            $produk->update($validated);
            $produk->load('kategoriProduk'); // Load relasi untuk response

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui!',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui produk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Menghapus produk dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!'
            ], 404);
        }

        try {
            // Hapus gambar - SAMA DENGAN WEB CONTROLLER
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $produk->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus produk: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk. Silakan coba lagi.'
            ], 500);
        }
    }
}
