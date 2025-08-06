<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriProduk::orderBy('nama')->get();
        return view('kategori_produk.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori_produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:45|unique:kategori_produks',
        ]);

        KategoriProduk::create($validated);

        return redirect()->route('kategori_produk.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriProduk $kategoriProduk)
    {
        return view('kategori_produk.show', compact('kategoriProduk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriProduk $kategoriProduk)
    {
        return view('kategori_produk.edit', compact('kategoriProduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriProduk $kategoriProduk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:45|unique:kategori_produks,nama,'.$kategoriProduk->id,
        ]);

        $kategoriProduk->update($validated);

        return redirect()->route('kategori_produk.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriProduk $kategoriProduk)
    {
        $kategoriProduk->delete();

        return redirect()->route('kategori_produk.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
