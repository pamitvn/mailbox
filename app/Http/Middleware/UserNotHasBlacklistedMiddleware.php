<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserNotHasBlacklistedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return $next($request);

        $user = auth()->user();
        $blacklisted = $user->blacklisted()->with('byUser')->first();

        if (blank($blacklisted)) return $next($request);
        return inertia('Errors/UserBanned', [
            'user' => $user,
            'blacklisted' => $blacklisted
        ]);
    }
}
