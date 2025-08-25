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
        ], [
            'img_profile.image' => 'File yang diunggah harus berupa gambar.',
            'img_profile.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'img_profile.max'   => 'Ukuran gambar maksimal 5MB.',
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
            'password' => 'required|string|min:8|max:16|confirmed',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.string'    => 'Password harus berupa teks.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.max'       => 'Password maksimal 16 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui.');
    }

    // 🔹 Update Data Umum
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'username'    => 'required|string|min:4|max:20|unique:users,username,' . $user->id,
            'nama'        => 'required|string|min:2|max:60',
            'no_telepon'  => 'required|string|min:10|max:13',
        ];

        if ($user->role === 'admin') {
            $rules['nama_usaha']   = 'required|string|min:4|max:60';
            $rules['alamat_usaha'] = 'required|string|min:5|max:60';
        }

        if ($user->role === 'pegawai') {
            $rules['alamat'] = 'required|string|min:5|max:60';
        }

        $messages = [
            'username.required' => 'Username wajib diisi.',
            'username.string'   => 'Username harus berupa teks.',
            'username.min'      => 'Username minimal 4 karakter.',
            'username.max'      => 'Username maksimal 20 karakter.',
            'username.unique'   => 'Username sudah digunakan, pilih yang lain.',

            'nama.required' => 'Nama wajib diisi.',
            'nama.string'   => 'Nama harus berupa teks.',
            'nama.min'      => 'Nama minimal 2 karakter.',
            'nama.max'      => 'Nama maksimal 60 karakter.',

            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.string'   => 'Nomor telepon harus berupa teks.',
            'no_telepon.min'      => 'Nomor telepon minimal 10 digit.',
            'no_telepon.max'      => 'Nomor telepon maksimal 13 digit.',

            'nama_usaha.required' => 'Nama usaha wajib diisi.',
            'nama_usaha.string' => 'Nama usaha harus berupa teks.',
            'nama_usaha.min'    => 'Nama usaha minimal 4 karakter.',
            'nama_usaha.max'    => 'Nama usaha maksimal 60 karakter.',

            'alamat_usaha.required' => 'Alamat usaha wajib diisi.',
            'alamat_usaha.string' => 'Alamat usaha harus berupa teks.',
            'alamat_usaha.min'    => 'Alamat usaha minimal 5 karakter.',
            'alamat_usaha.max'    => 'Alamat usaha maksimal 60 karakter.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string'   => 'Alamat harus berupa teks.',
            'alamat.min'      => 'Alamat minimal 5 karakter.',
            'alamat.max'      => 'Alamat maksimal 60 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        $user->fill($validated);
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
