<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    // Tampilkan semua pengeluaran
    public function index()
    {
        $pengeluaran = Pengeluaran::with('kategori')->latest()->get(); // Hilangkan Auth::id() jika tanpa login
        return view('pengeluaran.index', compact('pengeluaran'));
    }

    // Tampilkan form tambah pengeluaran
    public function create()
    {
        $kategori = KategoriPengeluaran::all();
        return view('pengeluaran.create', compact('kategori'));
    }

    // Simpan data pengeluaran
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:45',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:60',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ]);

        $validated['user_id'] = 1; // Sementara jika tanpa login
        Pengeluaran::create($validated);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // Tampilkan form edit pengeluaran
    public function edit(Pengeluaran $pengeluaran)
    {
        $kategori = KategoriPengeluaran::all();
        return view('pengeluaran.edit', compact('pengeluaran', 'kategori'));
    }

    // Perbarui data pengeluaran
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:45',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:60',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    // Hapus pengeluaran
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
