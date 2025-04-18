<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
