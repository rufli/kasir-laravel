<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    public function index()
    {
        return response()->json(KategoriProduk::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            // Update the table name here to match the model
            'nama' => 'required|string|max:45|unique:kategori_produks',
        ]);
        $kategori = KategoriProduk::create($request->only('nama'));
        return response()->json($kategori, 201);
    }

    public function show(KategoriProduk $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriProduk $kategori)
    {
        $request->validate([
            // Update the table name here to match the model
            'nama' => 'required|string|max:45|unique:kategori_produks,nama,' . $kategori->id,
        ]);
        $kategori->update($request->only('nama'));
        return response()->json($kategori);
    }

    public function destroy(KategoriProduk $kategori)
    {
        $kategori->delete();
        return response()->json(['message' => 'Kategori dihapus']);
    }
}
