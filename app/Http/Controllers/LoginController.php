<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        // Ambil data username & password saja
        $credentials = $request->only('username', 'password');
        $remember = $request->filled('remember');

        // Proses login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pastikan user aktif
            if (!$user->is_active) {
                Auth::logout();
                return redirect()->back()
                    ->withErrors(['username' => 'Akun tidak aktif'])
                    ->withInput();
            }

            // Redirect berdasarkan role
            return match ($user->role) {
                'admin'   => redirect()->intended('/dashboard'),
                'pegawai' => redirect()->intended('/produk'),
                default   => redirect()->intended('/'),
            };
        }

        // Jika login gagal
        return redirect()->back()
            ->withErrors(['username' => 'Username atau password salah'])
            ->withInput();
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
