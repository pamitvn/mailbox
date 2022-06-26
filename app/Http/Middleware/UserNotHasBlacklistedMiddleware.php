<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class UserNotHasBlacklistedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return $next($request);

        $user = auth()->user();
        $blacklisted = $user->blacklisted()->with('byUser')->first();
        $duration = Carbon::parse($blacklisted?->duration);
        $now = now();

        if (blank($blacklisted)) return $next($request);

        if ($blacklisted?->duration and $now >= $duration) {
            $blacklisted->delete();
            return $next($request);
        }

        return inertia('Errors/UserBanned', [
            'user' => $user,
            'blacklisted' => $blacklisted
        ]);
    }
}
