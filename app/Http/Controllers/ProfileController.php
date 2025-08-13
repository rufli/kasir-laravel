<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        return view('user.edit', [
            'user' => $user,
            'nama_usaha' => $user->nama_usaha ?? '-',
            'alamat_usaha' => $user->alamat_usaha ?? '-',
            'nama' => $user->nama,
            'email' => $user->email ?? '-',
            'alamat' => $user->alamat,
            'noTelepon' => $user->no_telepon,
            'role' => $user->role ?? '-',
        ]);
    }


    public function index()
    {
        $user = auth()->user();

        return view('user.index', [
            'user' => $user,
            'nama' => $user->nama,
            'email' => $user->email ?? '-',
            'alamat' => $user->alamat,
            'noTelepon' => $user->no_telepon,
            'role' => $user->role ?? '-',
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'required|string|min:2|max:45|unique:users,username,' . $user->id,
            'nama' => 'required|string|min:2|max:60',
            'no_telepon' => 'required|string|min:7|max:13',
            'img_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'password' => 'nullable|string|min:8|confirmed',
            // validasi tambahan sesuai role
            'nama_usaha' => $user->role === 'admin' ? 'nullable|string|min:2|max:100' : '',
            'alamat_usaha' => $user->role === 'admin' ? 'nullable|string|min:2|max:255' : '',
            'alamat' => $user->role === 'pegawai' ? 'required|string|min:2|max:255' : '',
        ]);

        // upload foto
        if ($request->hasFile('img_profile') && $request->file('img_profile')->isValid()) {
            if ($user->img_profile && Storage::exists('public/' . $user->img_profile)) {
                Storage::delete('public/' . $user->img_profile);
            }
            $user->img_profile = $request->file('img_profile')->store('images', 'public');
        }

        // update field umum
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->no_telepon = $request->no_telepon;

        // update sesuai role
        if ($user->role === 'admin') {
            $user->nama_usaha = $request->nama_usaha;
            $user->alamat_usaha = $request->alamat_usaha;
        }

        if ($user->role === 'pegawai') {
            $user->alamat = $request->alamat;
        }

        // update password jika ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
