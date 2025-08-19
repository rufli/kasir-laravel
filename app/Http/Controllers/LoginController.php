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
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Siapkan kredensial dengan tambahan pengecekan status aktif
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'is_active' => true, // HANYA izinkan login jika akun aktif
        ];

        // Cek apakah Remember Me dicentang
        $remember = $request->has('remember');

        // Proses login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Ambil role user
            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard');
            } elseif ($user->role === 'pegawai') {
                return redirect()->intended('/produk');
            } else {
                // Default kalau role tidak dikenali
                return redirect()->intended('/');
            }
        }

        // Jika login gagal
        return back()->with('errorLogin', 'Username atau password salah atau akun tidak aktif')->withInput();
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
