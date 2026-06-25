<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin','super_admin'])) {
            abort(403);
        }

        return $next($request);
    }
}