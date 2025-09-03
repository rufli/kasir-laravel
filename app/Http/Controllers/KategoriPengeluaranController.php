<?php

namespace App\Http\Controllers;

use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
    /**
     * Tampilkan daftar kategori.
     */
    public function index()
    {
        $kategori = KategoriPengeluaran::orderBy('nama')->get();
        return view('kategori_pengeluaran.index', compact('kategori'));
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create()
    {
        return view('kategori_pengeluaran.create');
    }

    /**
     * Simpan kategori baru.
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
                'unique:kategori_pengeluaran,nama',
            ],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.min' => 'Nama kategori minimal 3 karakter.',
            'nama.max' => 'Nama kategori maksimal 45 karakter.',
            'nama.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'nama.unique' => 'Nama kategori sudah terdaftar.',
        ]);

        KategoriPengeluaran::create($validated);

        return redirect()->route('kategori_pengeluaran.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(KategoriPengeluaran $kategori_pengeluaran)
    {
        return view('kategori_pengeluaran.edit', compact('kategori_pengeluaran'));
    }

    /**
     * Perbarui kategori.
     */
    public function update(Request $request, KategoriPengeluaran $kategori_pengeluaran)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:36',
                'regex:/^[A-Za-z\s]+$/',
                'unique:kategori_pengeluaran,nama,' . $kategori_pengeluaran->id,
            ],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.min' => 'Nama kategori minimal 3 karakter.',
            'nama.max' => 'Nama kategori maksimal 36 karakter.',
            'nama.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi.',
            'nama.unique' => 'Nama kategori sudah ada',
        ]);

        $kategori_pengeluaran->update($validated);

        return redirect()->route('kategori_pengeluaran.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     */
    public function destroy(KategoriPengeluaran $kategori_pengeluaran)
    {
        $kategori_pengeluaran->delete();

        return redirect()->route('kategori_pengeluaran.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
