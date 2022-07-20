<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\PAM\Facades\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class APIAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->bearerToken() ?? $request->query('apiKey');

        $user = User::where('api_key', $apiToken)->first();

        if (! $apiToken || ! $user) {
            return response()->json(ApiResponse::withFailed()->withMessage(__('Not Authorized')));
        }

        auth()->login($user);

        return $next($request);
    }
}
