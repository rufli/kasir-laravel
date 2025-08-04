<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 400));
        }

        // Proses login
        if (Auth::attempt($request->only(['username', 'password']))) {
            $user = auth()->user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                "token" => $token,
                "user" => $user
            ], 200);
        }

        throw new HttpResponseException(response()->json([
            "error" => [
                "message" => "Username atau password salah"
            ]
        ], 401));
    }

    public function logout(Request $request)
    {
        // Hapus semua token user yang login
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Token berhasil dihapus']);
    }
}
