<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Log;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Mengambil semua produk dari database, dengan relasi kategoriProduk
        $produks = Produk::with('kategoriProduk')->get();

        // Mengembalikan respons JSON dengan data produk
        return response()->json([
            'success' => true,
            'message' => 'Daftar Produk',
            'data' => $produks
        ], 200);
    }

    /**
     * Menyimpan produk baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:45', // Aturan 'unique' dihapus di sini
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'kategori_produk_id' => 'required|integer|exists:kategori_produks,id', // Perbaikan: Mengubah 'kategori_produk' menjadi 'kategori_produks'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal!',
                'errors' => $validator->errors()
            ], 400); // Bad Request
        }

        try {
            // Membuat produk baru
            $produk = Produk::create([
                'tanggal' => $request->tanggal,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'kategori_produk_id' => $request->kategori_produk_id,
            ]);

            // Mengembalikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan!',
                'data' => $produk
            ], 201); // Created
        } catch (\Exception $e) {
            // Menangkap error jika terjadi masalah saat menyimpan
            Log::error('Gagal menambahkan produk: ' . $e->getMessage()); // Catat error
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk. Silakan coba lagi.' // Pesan umum untuk klien
            ], 500); // Internal Server Error
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
        // Mencari produk berdasarkan ID, dengan relasi kategoriProduk
        $produk = Produk::with('kategoriProduk')->find($id);

        // Jika produk tidak ditemukan
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!'
            ], 404); // Not Found
        }

        // Mengembalikan respons JSON dengan data produk
        return response()->json([
            'success' => true,
            'message' => 'Detail Produk',
            'data' => $produk
        ], 200);
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
        // Mencari produk berdasarkan ID
        $produk = Produk::find($id);

        // Jika produk tidak ditemukan
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!'
            ], 404); // Not Found
        }

        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'tanggal' => 'sometimes|required|date',
            'nama' => 'sometimes|required|string|max:45', // Aturan 'unique' dihapus di sini
            'harga' => 'sometimes|required|numeric|min:0',
            'stok' => 'sometimes|required|integer|min:0',
            'kategori_produk_id' => 'sometimes|required|integer|exists:kategori_produks,id', // Perbaikan: Mengubah 'kategori_produk' menjadi 'kategori_produks'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal!',
                'errors' => $validator->errors()
            ], 400); // Bad Request
        }

        try {
            // Memperbarui data produk
            $produk->update($request->all());

            // Mengembalikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui!',
                'data' => $produk
            ], 200); // OK
        } catch (\Exception $e) {
            // Menangkap error jika terjadi masalah saat memperbarui
            Log::error('Gagal memperbarui produk: ' . $e->getMessage()); // Catat error
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk. Silakan coba lagi.' // Pesan umum untuk klien
            ], 500); // Internal Server Error
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
        // Mencari produk berdasarkan ID
        $produk = Produk::find($id);

        // Jika produk tidak ditemukan
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!'
            ], 404); // Not Found
        }

        try {
            // Menghapus produk (ini akan melakukan soft delete jika model Produk menggunakan SoftDeletes)
            $produk->delete();

            // Mengembalikan respons JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus!'
            ], 200); // OK
        } catch (\Exception $e) {
            // Menangkap error jika terjadi masalah saat menghapus
            Log::error('Gagal menghapus produk: ' . $e->getMessage()); // Catat error
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk. Silakan coba lagi.' // Pesan umum untuk klien
            ], 500); // Internal Server Error
        }
    }
}
