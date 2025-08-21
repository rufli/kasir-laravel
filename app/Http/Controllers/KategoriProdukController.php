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
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:45',
                'regex:/^[A-Za-z\s]+$/',
                'unique:kategori_produks,nama',
            ],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.min' => 'Nama kategori minimal 3 karakter.',
            'nama.max' => 'Nama kategori maksimal 45 karakter.',
            'nama.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'nama.unique' => 'Nama kategori sudah terdaftar.',
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
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:45',
                'regex:/^[A-Za-z\s]+$/',
                'unique:kategori_produks,nama,' . $kategoriProduk->id,
            ],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.min' => 'Nama kategori minimal 3 karakter.',
            'nama.max' => 'Nama kategori maksimal 45 karakter.',
            'nama.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'nama.unique' => 'Nama kategori sudah ada.',
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
