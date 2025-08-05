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
        $kategoris = KategoriProduk::paginate(10);
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
        $request->validate([
            'nama' => 'required|string|max:45|unique:kategori_produk'
        ]);

        KategoriProduk::create($request->only(['nama']));

        return redirect()->route('kategori_produk.index')
                         ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriProduk $kategoriProduk)
    {
        //
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
        $request->validate([
            'nama' => 'required|string|max:45|unique:kategori_produk,nama,' . $kategoriProduk->id
        ]);

        $kategoriProduk->update($request->only(['nama']));

        return redirect()->route('kategori_produk.index')
                         ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriProduk $kategoriProduk)
    {
       $kategoriProduk->delete();

        return redirect()->route('kategori_produk.index')
                         ->with('success', 'Kategori produk berhasil dihapus.');
    }
}
