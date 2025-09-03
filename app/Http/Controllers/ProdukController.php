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
            'tanggal' => 'required|date|before_or_equal:today',
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:45',
                'regex:/^[A-Za-z0-9\s]+$/', // huruf, angka, spasi
            ],
            'harga' => 'required|numeric|min:0|max:99999999.99',
            'stok' => 'required|integer|min:0|max:100000',
            'gambar' => 'nullable|mimes:jpeg,png,jpg,gif|max:5048',
            'kategori_produk_id' => 'required|exists:kategori_produks,id',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
            'nama.required' => 'Nama produk wajib diisi.',
            'nama.min' => 'Nama produk minimal 3 karakter.',
            'nama.max' => 'Nama produk maksimal 45 karakter.',
            'nama.regex' => 'Nama produk hanya boleh berisi huruf, angka, dan spasi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
            'harga.max' => 'Harga maksimal 99.999.999,99.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok minimal 0.',
            'stok.max' => 'Stok maksimal 100.000.',
            'gambar.mimes' => 'Format gambar harus jpeg, png,atau jpg.',
            'gambar.max' => 'Ukuran gambar maksimal 5MB.',
            'kategori_produk_id.required' => 'Kategori produk wajib dipilih.',
            'kategori_produk_id.exists' => 'Kategori produk tidak valid.',
        ]);

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
            'tanggal' => 'required|date|before_or_equal:today',
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:45',
                'regex:/^[A-Za-z0-9\s]+$/',
            ],
            'harga' => 'required|numeric|min:0|max:99999999.99',
            'stok' => 'required|integer|min:0|max:100000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_produk_id' => 'required|exists:kategori_produks,id',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
            'nama.required' => 'Nama produk wajib diisi.',
            'nama.min' => 'Nama produk minimal 3 karakter.',
            'nama.max' => 'Nama produk maksimal 45 karakter.',
            'nama.regex' => 'Nama produk hanya boleh berisi huruf, angka, dan spasi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
            'harga.max' => 'Harga maksimal 99.999.999,99.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok minimal 0.',
            'stok.max' => 'Stok maksimal 100.000.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'kategori_produk_id.required' => 'Kategori produk wajib dipilih.',
            'kategori_produk_id.exists' => 'Kategori produk tidak valid.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
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
