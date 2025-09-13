<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    // Tampilkan semua pengeluaran
    public function index(Request $request)
    {
        $query = Pengeluaran::with('kategori');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // cari berdasarkan nama pengeluaran
                $q->where('nama', 'like', '%' . $search . '%')
                    // cari juga berdasarkan nama kategori (relasi)
                    ->orWhereHas('kategori', function ($q2) use ($search) {
                        $q2->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        $pengeluaran = $query->latest()->get();

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
            'nama' => 'required|string|min:3|max:20',
            'satuan' => 'required|string|min:1|max:10',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:60',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ], [
            'tanggal.required' => 'tanggal wajib di isi',
            'nama.required' => 'nama tidak boleh kosong',
            'nama.min' => 'nama harus lebih dari 2 karakter',
            'nama.max' => 'nama tidak boleh lebih dari 20 karakter',
            'satuan.required' => 'satuan wajib di isi',
            'satuan.min' => 'satuan harus lebih dari 0 karakter',
            'satuan.max' => 'satuan tidak boleh lebih dari 10 karakter',
            'jumlah.required' => 'jumlah wajib di isi',
            'jumlah.numeric' => 'jumlah harus berupa angka',
            'jumlah.min' => 'jumlah tidak boleh kurang dari 0',
            'kategori_pengeluaran_id.required' => 'kategori wajib di isi',
            'kategori_pengeluaran_id.exists' => 'kategori tidak valid',
        ]);

        $validated['user_id'] = 1; // sementara jika tanpa login
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
            'nama' => 'required|string|min:3|max:20',
            'satuan' => 'required|string|min:1|max:10',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:60',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ], [
            'tanggal.required' => 'tanggal wajib di isi',
            'nama.required' => 'nama tidak boleh kosong',
            'nama.min' => 'nama harus lebih dari 2 karakter',
            'nama.max' => 'nama tidak boleh lebih dari 20 karakter',
            'satuan.required' => 'satuan wajib di isi',
            'satuan.min' => 'satuan harus lebih dari 0 karakter',
            'satuan.max' => 'satuan tidak boleh lebih dari 10 karakter',
            'jumlah.required' => 'jumlah wajib di isi',
            'jumlah.numeric' => 'jumlah harus berupa angka',
            'jumlah.min' => 'jumlah tidak boleh kurang dari 0',
            'kategori_pengeluaran_id.required' => 'kategori wajib di isi',
            'kategori_pengeluaran_id.exists' => 'kategori tidak valid',
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
