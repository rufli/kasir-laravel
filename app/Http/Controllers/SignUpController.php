<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pegawai');

        if ($request->has('search') && $request->search !== null) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $daftarPegawai = $query->select('id', 'nama', 'username', 'no_telepon', 'alamat')->get();

        return view('pegawai.index', compact('daftarPegawai'));
    }


    public function create()
    {
        return view('pegawai.create');
    }

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

        User::create($validate);

        return redirect()->route('pegawai.index')->with('success', 'Akun pegawai berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $validate = $request->validate([
            "nama"        => "required|string|min:2|max:60",
            "username"    => "required|string|min:3|max:45|unique:users,username,$id",
            "no_telepon"  => "nullable|string|min:8|max:20|unique:users,no_telepon,$id",
            "password"    => "nullable|string|min:8|max:16",
            "alamat"      => "nullable|string|min:4|max:60",
        ]);

        if (!empty($validate['password'])) {
            $validate['password'] = Hash::make($validate['password']);
        } else {
            unset($validate['password']);
        }

        $pegawai->update($validate);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diupdate.');
    }

    public function destroy($id)
    {
        $pegawai = User::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
