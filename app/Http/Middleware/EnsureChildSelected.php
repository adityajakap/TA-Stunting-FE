<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * EnsureChildSelected
 * Checks that a selected_child_id is in the session.
 * If not, redirect to dashboard with a notice.
 */
class EnsureChildSelected
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('selected_child_id')) {
            return redirect()->route('orangtua.dashboard')
                ->with('warning', 'Silakan pilih anak terlebih dahulu.');
        }

        return $next($request);
    }
}
