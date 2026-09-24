<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsPremium
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_premium) {
            return redirect('/premium')->with('error', 'Passez à Premium pour accéder à cette fonctionnalité.');
        }
        return $next($request);
    }
}
