<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * EnsureApiAuthenticated
 * Checks that the user has a valid api_token stored in session.
 * Redirects to login if not.
 */
class EnsureApiAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('api_token') || !session('user')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
