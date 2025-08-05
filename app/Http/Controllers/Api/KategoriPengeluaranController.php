<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    /**
     * Tampilkan semua kategori (JSON).
     */
    public function index()
    {
        $kategori = KategoriPengeluaran::all();

        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    /**
     * Form create tidak digunakan dalam API.
     */
    public function create()
    {
        return response()->json([
            'message' => 'Endpoint ini tidak digunakan untuk JSON.'
        ], 404);
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:45|unique:kategori_pengeluaran,nama',
        ]);

        $kategori = KategoriPengeluaran::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $kategori
        ], 201);
    }

    /**
     * Tampilkan detail kategori tertentu.
     */
    public function show(KategoriPengeluaran $kategoriPengeluaran)
    {
        return response()->json([
            'success' => true,
            'data' => $kategoriPengeluaran
        ]);
    }

    /**
     * Hapus kategori.
     */
    public function destroy(KategoriPengeluaran $kategoriPengeluaran)
    {
        $kategoriPengeluaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.'
        ]);
    }
}
