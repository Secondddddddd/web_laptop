<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StorePreviousUrl
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('login') && !$request->is('register') && !$request->is('password/*')) {
            session(['url.intended' => $request->fullUrl()]);
        }
        return $next($request);
    }
}
