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

        // Validasi umum tanpa password
        $rules = [
            'username' => 'required|string|min:2|max:45|unique:users,username,' . $user->id,
            'nama' => 'required|string|min:2|max:60',
            'no_telepon' => 'required|string|min:7|max:13',
            'img_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            // Role spesifik
            'nama_usaha' => $user->role === 'admin' ? 'nullable|string|min:2|max:100' : '',
            'alamat_usaha' => $user->role === 'admin' ? 'nullable|string|min:2|max:255' : '',
            'alamat' => $user->role === 'pegawai' ? 'required|string|min:2|max:255' : '',
        ];

        $request->validate($rules);

        // Validasi password hanya jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }

        // Upload foto profil
        if ($request->hasFile('img_profile') && $request->file('img_profile')->isValid()) {
            // Hapus file lama jika ada
            if ($user->img_profile && Storage::exists('public/' . $user->img_profile)) {
                Storage::delete('public/' . $user->img_profile);
            }
            // Simpan foto baru
            $user->img_profile = $request->file('img_profile')->store('images', 'public');
        }

        // Update field umum
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->no_telepon = $request->no_telepon;

        // Update role spesifik
        if ($user->role === 'admin') {
            $user->nama_usaha = $request->nama_usaha;
            $user->alamat_usaha = $request->alamat_usaha;
        }

        if ($user->role === 'pegawai') {
            $user->alamat = $request->alamat;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
