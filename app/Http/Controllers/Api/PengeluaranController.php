<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    /**
     * Tampilkan semua pengeluaran milik user (JSON).
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::with('kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pengeluaran
        ]);
    }

    /**
     * Endpoint tidak digunakan (untuk API).
     */
    public function create()
    {
        return response()->json([
            'message' => 'Endpoint ini tidak digunakan.'
        ], 404);
    }

    /**
     * Simpan pengeluaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:45',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:60',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ]);

        $validated['user_id'] = Auth::id();

        $pengeluaran = Pengeluaran::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil ditambahkan.',
            'data' => $pengeluaran
        ], 201);
    }

    /**
     * Tampilkan detail pengeluaran tertentu.
     */
    public function show(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $pengeluaran->load('kategori')
        ]);
    }

    /**
     * Endpoint tidak digunakan (untuk API).
     */
    public function edit(Pengeluaran $pengeluaran)
    {
        return response()->json([
            'message' => 'Endpoint ini tidak digunakan.'
        ], 404);
    }

    /**
     * Perbarui pengeluaran.
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:45',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:60',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ]);

        $pengeluaran->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diperbarui.',
            'data' => $pengeluaran
        ]);
    }
}
