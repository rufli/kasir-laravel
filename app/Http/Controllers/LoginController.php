<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        // Validasi input manual menggunakan Validator
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        // Jika validasi gagal, kembalikan ke halaman login dengan error
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Coba login
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate(); // Hindari session fixation
            return redirect()->intended('/dashboard'); // Redirect sesuai tujuan
        }

        // Jika login gagal
        return back()->with('errorLogin', 'Username atau password salah')->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Berhasil logout');
    }
}
