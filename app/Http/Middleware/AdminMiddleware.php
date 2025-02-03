<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roleName): Response
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }
        
        // Ambil pengguna yang sedang login
        $user = Auth::user();
        // dd($user->roles);
        
        $hasRole = $user->roles->contains('id', $roleName);

        if (!$hasRole) {
            return redirect()->back()->with('error', 'Unauthorized access!');
        }

        return $next($request);
    }
}
