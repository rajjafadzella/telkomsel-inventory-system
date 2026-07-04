<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil nama role dari user yang sedang login
        $userRole = Auth::user()->role->name;

        // 3. Cek apakah role user ada di dalam daftar role yang diizinkan mengakses halaman
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak punya hak akses, lemparkan error 403 (Unauthorized)
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
