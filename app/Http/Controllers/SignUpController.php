<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    /**
     * Menampilkan daftar pegawai dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'pegawai');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $daftarPegawai = $query->withCount('transaksiPenjualans')
                              ->select('id', 'nama', 'username', 'no_telepon', 'alamat', 'is_active')
                              ->get();

        return view('pegawai.index', compact('daftarPegawai'));
    }

    /**
     * Menampilkan form tambah pegawai.
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Simpan pegawai baru.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "nama"        => "required|string|min:2|max:60",
            "username"    => "required|string|min:4|max:20|unique:users",
            "no_telepon"  => "nullable|string|min:10|max:13|unique:users,no_telepon",
            "password"    => "required|string|min:8|max:16",
            "alamat"      => "nullable|string|min:5|max:60",
        ], [
            "nama.required"       => "Nama wajib diisi.",
            "nama.min"            => "Nama minimal 2 karakter.",
            "nama.max"            => "Nama maksimal 60 karakter.",
            

            "username.required"   => "Username wajib diisi.",
            "username.min"        => "Username minimal 4 karakter.",
            "username.max"        => "Username maksimal 20 karakter.",
            "username.unique"     => "Username sudah digunakan.",

            "no_telepon.min"      => "Nomor telepon minimal 10 digit.",
            "no_telepon.max"      => "Nomor telepon maksimal 13 digit.",
            "no_telepon.unique"   => "Nomor telepon sudah digunakan.",

            "password.required"   => "Password wajib diisi.",
            "password.min"        => "Password minimal 8 karakter.",
            "password.max"        => "Password maksimal 16 karakter.",

            "alamat.min"          => "Alamat minimal 5 karakter.",
            "alamat.max"          => "Alamat maksimal 60 karakter.",
        ]);

        $validate['password'] = bcrypt($validate['password']);
        $validate['role'] = 'pegawai';
        $validate['is_active'] = true;

        User::create($validate);

        return redirect()->route('pegawai.index')->with('success', 'Akun pegawai berhasil ditambahkan.');
    }

    /**
     * Edit pegawai.
     */
    public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Update pegawai.
     */
    public function update(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $validate = $request->validate([
            "nama"        => "required|string|min:2|max:60",
            "username"    => "required|string|min:4|max:20|unique:users,username,$id",
            "no_telepon"  => "nullable|string|min:10|max:13|unique:users,no_telepon,$id",
            "password"    => "nullable|string|min:8|max:16",
            "alamat"      => "nullable|string|min:5|max:60",
            "is_active"   => "nullable|boolean",
        ], [
            "nama.required"       => "Nama wajib diisi.",
            "nama.min"            => "Nama minimal 2 karakter.",
            "nama.max"            => "Nama maksimal 60 karakter.",

            "username.required"   => "Username wajib diisi.",
            "username.min"        => "Username minimal 4 karakter.",
            "username.max"        => "Username maksimal 20 karakter.",
            "username.unique"     => "Username sudah digunakan.",


            "no_telepon.min"      => "Nomor telepon minimal 10 digit.",
            "no_telepon.max"      => "Nomor telepon maksimal 13 digit.",
            "no_telepon.unique"   => "Nomor telepon sudah digunakan.",

            "password.min"        => "Password minimal 8 karakter.",
            "password.max"        => "Password maksimal 16 karakter.",

            "alamat.min"          => "Alamat minimal 5 karakter.",
            "alamat.max"          => "Alamat maksimal 60 karakter.",
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
     * Toggle status aktif pegawai.
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
     * Hapus pegawai.
     */
    public function destroy($id)
    {
        $pegawai = User::findOrFail($id);

        if ($pegawai->transaksiPenjualans()->exists()) {
            return redirect()->route('pegawai.index')->with('error', 'Akun pegawai tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
