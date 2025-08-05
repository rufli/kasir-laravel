<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        Log::info('Authenticated user:', ['user' => $user]);
        Log::info('User role:', ['role' => $user->role]);

        // Cek apakah user punya role (pastikan tidak null)
        if (!$user->role) {
            return response()->json(['message' => 'Role not found'], 403);
        }

        $userRole = $user->role; // Karena role disimpan sebagai kolom biasa

        // Validasi apakah role pengguna termasuk dalam yang diizinkan
        if (!in_array($userRole, $roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
