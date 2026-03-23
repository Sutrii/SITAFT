<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if ($role === 'mahasiswa') {
            if ($user->roleId == 1 && $user->positionId == 3) {
                return $next($request);
            }
            
            // Redirect Koordinator/Dosen away from Mahasiswa pages
            return redirect()->route('koordinator.dashboard')->withErrors(['error' => 'Akses ditolak: Area ini dikhususkan untuk Mahasiswa.']);
        }

        if ($role === 'koordinator') {
            if ($user->roleId == 2 || $user->positionId == 1) {
                return $next($request);
            }
            
            // Redirect Mahasiswa away from Koordinator pages
            if ($user->roleId == 1 && $user->positionId == 3) {
                return redirect()->route('dashboard.mahasiswa')->withErrors(['error' => 'Akses ditolak: Anda tidak memiliki izin untuk halaman Koordinator.']);
            }
        }

        // Fallback catch-all for undefined users
        return redirect()->route('dashboard')->withErrors(['error' => 'Akses ditolak: Role tidak valid.']);
    }
}
