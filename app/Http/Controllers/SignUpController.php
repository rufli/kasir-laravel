<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    /**
     * Menampilkan daftar pegawai dengan fitur pencarian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'pegawai');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // Tambahkan kolom 'is_active' dan hitung jumlah transaksi
        $daftarPegawai = $query->withCount('transaksiPenjualans')
                              ->select('id', 'nama', 'username', 'no_telepon', 'alamat', 'is_active')
                              ->get();

        return view('pegawai.index', compact('daftarPegawai'));
    }

    /**
     * Menampilkan formulir untuk membuat pegawai baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Menyimpan pegawai baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "nama"        => "required|string|min:2|max:60",
            "username"    => "required|string|min:3|max:45|unique:users",
            "no_telepon"  => "nullable|string|min:8|max:20",
            "password"    => "required|string|min:8|max:16",
            "alamat"      => "nullable|string|min:4|max:60",
        ]);

        $validate['password'] = bcrypt($validate['password']);
        $validate['role'] = 'pegawai';
        $validate['is_active'] = true;

        User::create($validate);

        return redirect()->route('pegawai.index')->with('success', 'Akun pegawai berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit pegawai.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Memperbarui data pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $validate = $request->validate([
            "nama"        => "required|string|min:2|max:60",
            "username"    => "required|string|min:3|max:45|unique:users,username,$id",
            "no_telepon"  => "nullable|string|min:8|max:20|unique:users,no_telepon,$id",
            "password"    => "nullable|string|min:8|max:16",
            "alamat"      => "nullable|string|min:4|max:60",
            "is_active"   => "nullable|boolean",
        ]);

        if (!empty($validate['password'])) {
            $validate['password'] = Hash::make($validate['password']);
        } else {
            unset($validate['password']);
        }

        $pegawai->update($validate);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diupdate.');
    }

    /**
     * Mengubah status aktif/non-aktif pegawai.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus($id)
    {
        $pegawai = User::findOrFail($id);
        $pegawai->is_active = !$pegawai->is_active;
        $pegawai->save();

        $status = $pegawai->is_active ? 'aktifkan' : 'non-aktifkan';
        return redirect()->route('pegawai.index')->with('success', "Akun pegawai berhasil di{$status}.");
    }

    /**
     * Menghapus pegawai, hanya jika tidak ada transaksi.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $pegawai = User::findOrFail($id);

        // Pengecekan server-side
        if ($pegawai->transaksiPenjualans()->exists()) {
            return redirect()->route('pegawai.index')->with('error', 'Akun pegawai tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
