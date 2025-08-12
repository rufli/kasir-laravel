<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /* ---------- INDEX ---------- */
  public function index(Request $request)
{
    $query = Produk::with('kategoriProduk')->latest();

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%");
        });
    }

    $produks = $query->paginate(10)->withQueryString();

    return view('produk.index', compact('produks'));
}



    /* ---------- CREATE ---------- */
    public function create()
    {
        $kategoris = KategoriProduk::all();
        return view('produk.create', compact('kategoris'));
    }

    /* ---------- STORE ---------- */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'            => 'required|date',
            'nama'               => 'required|string|max:45',
            'harga'              => 'required|numeric|min:0|max:99999999.99',
            'stok'               => 'required|integer|min:0',
            'gambar'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_produk_id' => 'required|exists:kategori_produks,id',
        ]);

        // simpan file kalau ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($validated);

        return redirect()->route('produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    /* ---------- SHOW ---------- */
    public function show(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    /* ---------- EDIT ---------- */
    public function edit(Produk $produk)
    {
        $kategoris = KategoriProduk::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    /* ---------- UPDATE ---------- */
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'tanggal'            => 'required|date',
            'nama'               => 'required|string|max:45',
            'harga'              => 'required|numeric|min:0|max:99999999.99',
            'stok'               => 'required|integer|min:0',
            'gambar'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_produk_id' => 'required|exists:kategori_produks,id',
        ]);

        if ($request->hasFile('gambar')) {
            // hapus gambar lama
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            // simpan gambar baru
            $validated['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('produk.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    /* ---------- DESTROY ---------- */
    public function destroy(Produk $produk)
    {
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }
        $produk->delete();

        return redirect()->route('produk.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}
