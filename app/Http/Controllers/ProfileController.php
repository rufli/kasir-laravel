<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('user.edit', compact('user'));
    }

    public function index()
    {
        $user = auth()->user();
        return view('user.index', compact('user'));
    }

    // 🔹 Update Foto Profil
    public function updateFoto(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'img_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Hapus foto lama
        if ($request->has('delete_img') && $user->img_profile) {
            if (Storage::exists('public/' . $user->img_profile)) {
                Storage::delete('public/' . $user->img_profile);
            }
            $user->img_profile = null;
        }

        // Upload foto baru
        if ($request->hasFile('img_profile') && $request->file('img_profile')->isValid()) {
            if ($user->img_profile && Storage::exists('public/' . $user->img_profile)) {
                Storage::delete('public/' . $user->img_profile);
            }
            $user->img_profile = $request->file('img_profile')->store('images', 'public');
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Foto profil berhasil diperbarui.');
    }

    // 🔹 Update Password
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Foto profil berhasil diperbarui.');
    }

    // 🔹 Update Data Umum (nama, username, alamat, dll.)
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'username' => 'required|string|min:2|max:45|unique:users,username,' . $user->id,
            'nama' => 'required|string|min:2|max:60',
            'no_telepon' => 'required|string|min:7|max:13',
        ];

        if ($user->role === 'admin') {
            $rules['nama_usaha'] = 'nullable|string|min:2|max:100';
            $rules['alamat_usaha'] = 'nullable|string|min:2|max:255';
        }

        if ($user->role === 'pegawai') {
            $rules['alamat'] = 'required|string|min:2|max:255';
        }

        $validated = $request->validate($rules);

        $user->fill($validated);
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
