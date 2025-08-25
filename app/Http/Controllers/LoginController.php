<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi form login
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:4|max:20',
            'password' => 'required|string|min:8|max:72',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min'      => 'Username minimal 4 karakter.',
            'username.max'      => 'Username maksimal 20 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.max'      => 'Password maksimal 72 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan'])->withInput();
        }

        // ✅ Cek apakah user aktif
        if (!$user->is_active) {
            return back()->withErrors(['username' => 'Akun tidak aktif'])->withInput();
        }

        // ✅ Cek password manual
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Password salah'])->withInput();
        }

        // ✅ Login user
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        // ✅ Redirect sesuai role
        return match ($user->role) {
            'admin'   => redirect()->intended('/dashboard'),
            'pegawai' => redirect()->intended('/produk'),
            default   => redirect()->intended('/'),
        };
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
