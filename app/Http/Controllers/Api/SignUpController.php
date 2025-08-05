<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
    public function daftarPegawai()
    {
        $daftarPegawai = User::where('role', 'pegawai')
            ->select('id', 'nama', 'username', 'no_telepon', 'alamat')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $daftarPegawai
        ], 200);
    }

    public function register(Request $request)
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

        $pegawai = User::create($validate);

        return response()->json([
            'status' => 'success',
            'message' => 'Akun Pegawai Berhasil Ditambah',
            'data' => $pegawai
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pegawai = User::find($id);

        if (!$pegawai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Pegawai tidak ditemukan'
            ], 404);
        }

        $validate = $request->validate([
            "nama"        => "required|string|min:2|max:60",
            "username"    => "required|string|min:3|max:45|unique:users,username,$id",
            "no_telepon"  => "nullable|string|min:8|max:20|unique:users,no_telepon,$id",
            "password"    => "nullable|string|min:8|max:16",
            "alamat"      => "nullable|string|min:4|max:60",
        ]);

        if (isset($validate['password'])) {
            $validate['password'] = bcrypt($validate['password']);
        } else {
            unset($validate['password']);
        }

        $pegawai->update($validate);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pegawai berhasil diupdate',
            'data' => $pegawai
        ], 200);
    }


    public function destroy($id)
    {
        $pegawai = User::find($id);

        if (!$pegawai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Pegawai tidak ditemukan'
            ], 404);
        }

        $pegawai->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pegawai berhasil dihapus'
        ], 200);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ], 200);
    }
}
