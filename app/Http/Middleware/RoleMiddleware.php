<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/login')->with('warning', 'Akses ditolak: Area ini memerlukan hak akses ' . strtoupper($role));
        }

        return $next($request);
    }
}