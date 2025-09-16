<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksiPenjualan;
use App\Models\KategoriProduk;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    // 1. Menampilkan daftar produk
    public function daftarProduk(Request $request)
    {
        $query = Produk::query();

        // Filter hanya produk yang aktif
        $query->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_produk_id', $request->kategori);
        }

        $produks = $query->with('kategoriProduk')->get();
        $kategoriProduks = KategoriProduk::all(); // untuk dropdown

        return view('penjualan.daftar_produk', compact('produks', 'kategoriProduks'));
    }

    // 2. Tambah produk ke keranjang
    public function tambahKeKeranjang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $keranjang = session()->get('keranjang', []);
        $produkId = $request->produk_id;
        $jumlahBaru = $request->jumlah;

        $produk = Produk::findOrFail($produkId);

        // Hitung total jumlah di keranjang jika produk ini sudah ada
        $jumlahDiKeranjang = $keranjang[$produkId] ?? 0;
        $totalJumlah = $jumlahDiKeranjang + $jumlahBaru;

        // Validasi stok
        if ($produk->stok < $totalJumlah) {
            return redirect()->back()
                ->with('error_produk', [
                    'id' => $produk->id,
                    'pesan' => 'Stok tidak mencukupi.'
                ]);
        }

        // Jika stok cukup, tambahkan ke keranjang
        $keranjang[$produkId] = $totalJumlah;
        session()->put('keranjang', $keranjang);

        return redirect()->back();
    }


    // 3. Tampilkan halaman keranjang
    public function keranjang()
    {
        $keranjang = session()->get('keranjang', []);
        $produks = Produk::whereIn('id', array_keys($keranjang))->get();

        $items = [];
        $total = 0;

        foreach ($produks as $produk) {
            $jumlah = $keranjang[$produk->id];
            $subtotal = $produk->harga * $jumlah;
            $items[] = [
                'produk' => $produk,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return view('penjualan.keranjang', compact('items', 'total'));
    }

    // 4. Hapus produk dari keranjang (1 per klik)
    public function hapusDariKeranjang($id)
    {
        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$id])) {
            if ($keranjang[$id] > 1) {
                $keranjang[$id] -= 1;
            } else {
                unset($keranjang[$id]);
            }

            session()->put('keranjang', $keranjang);
        }

        return redirect()->back()->with('success', 'Produk dikurangi dari keranjang.');
    }

    // 5. Halaman pilih metode pembayaran
    public function checkoutPage()
    {
        $keranjang = session()->get('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->route('penjualan.keranjang')->with('error', 'Keranjang kosong.');
        }

        $produks = Produk::whereIn('id', array_keys($keranjang))->get();
        $total = 0;

        foreach ($produks as $produk) {
            $jumlah = $keranjang[$produk->id];
            $total += $produk->harga * $jumlah;
        }

        return view('penjualan.pembayaran', compact('total'));
    }

    // 6. Proses pembayaran & simpan transaksi
    public function checkout(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            'jumlah_dibayar'    => 'required|numeric|min:0'
        ], [
            'metode_pembayaran.required' => 'Metode Pembayaran wajib di isi',
            'metode_pembayaran.in'       => 'Metode Pembayaran tidak valid',
            'jumlah_dibayar.required'    => 'Jumlah uang wajib di isi',
            'jumlah_dibayar.numeric'     => 'Jumlah uang harus berupa angka',
        ]);

        $keranjang = session()->get('keranjang', []);
        if (empty($keranjang)) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $produkData = [];

            foreach ($keranjang as $produkId => $jumlah) {
                $produk = Produk::findOrFail($produkId);

                // Validasi stok
                if ($produk->stok < $jumlah) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Stok tidak cukup untuk produk: ' . $produk->nama);
                }
                // Kurangi stok produk
                $produk->stok -= $jumlah;
                $produk->save();

                $subtotal = $produk->harga * $jumlah;

                $produkData[] = [
                    'produk' => $produk,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal
                ];

                $totalHarga += $subtotal;
            }

            $jumlahDibayar = $request->jumlah_dibayar;

            // Validasi jumlah dibayar harus >= total harga
            if ($jumlahDibayar < $totalHarga) {
                DB::rollback(); // tambahkan rollback agar stok yang sudah dikurangi tidak tersimpan
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'jumlah_dibayar' => 'Jumlah yang dibayar kurang dari total harga. Silakan masukkan nominal yang sesuai.'
                    ]);
            }

            $jumlahKembalian = $jumlahDibayar - $totalHarga;

            $transaksi = TransaksiPenjualan::create([
                'tanggal'            => Carbon::now()->toDateString(),
                'total_harga'        => $totalHarga,
                'jumlah_dibayar'     => $jumlahDibayar,
                'jumlah_kembalian'   => $jumlahKembalian,
                'metode_pembayaran' => $request->metode_pembayaran,
                'users_id'           => Auth::id(),
            ]);

            foreach ($produkData as $item) {
                DetailTransaksiPenjualan::create([
                    'transaksi_penjualan_id' => $transaksi->id,
                    'produk_id'              => $item['produk']->id,
                    'jumlah'                 => $item['jumlah'],
                    'subtotal'               => $item['subtotal'],
                    // Simpan snapshot data produk
                    'nama_produk'            => $item['produk']->nama,
                     'harga_produk'           => $item['produk']->harga,
                ]);
            }

            DB::commit();
            session()->forget('keranjang');

            return redirect()->route('penjualan.detail', $transaksi->id)
                ->with('success', 'Pembayaran berhasil!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi.');
        }
    }

    // 7. Menampilkan detail transaksi (struk)
    public function detailTransaksi($id)
    {
        $query = TransaksiPenjualan::with(['detailTransaksi.produks', 'user'])
            ->where('id', $id);

        if (auth()->user()->role !== 'admin') {
            // Pegawai hanya boleh melihat transaksi sendiri
            $query->where('users_id', Auth::id());
        }

        $transaksi = $query->firstOrFail();

        // Ambil data toko (user dengan role admin)
        $toko = User::where('role', 'admin')->first();

        return view('penjualan.detail', compact('transaksi', 'toko'));
    }


    // 8. Riwayat transaksi
    public function riwayatTransaksi(Request $request)
    {
        $query = TransaksiPenjualan::with(['detailTransaksi.produks', 'user']);

        if (auth()->user()->role === 'admin') {
            // Filter user jika ada
            if ($request->filled('user_id')) {
                $query->where('users_id', $request->user_id);
            }
            $users = User::where('role', 'pegawai')->get();
        } else {
            // Pegawai hanya lihat transaksi sendiri
            $query->where('users_id', auth()->id());
            $users = collect();
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // ✅ Filter metode pembayaran
        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $riwayat = $query->orderBy('tanggal', 'desc')->get();

        return view('penjualan.riwayat', compact('riwayat', 'users'));
    }
}
