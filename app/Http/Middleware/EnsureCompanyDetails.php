<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureCompanyDetails
{
    public function handle(Request $request, Closure $next): Response
    {
        // Optional: log entry for debug
        // logger('EnsureCompanyDetails middleware running...');

        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user->company) {
            return $next($request);
        }

        $safeRoutes = [
            'company.required',
            'profile.edit',
            'profile.update',
            'logout',
        ];

        if ($request->routeIs($safeRoutes)) {
            return $next($request);
        }

        return redirect()->route('company.required');
    }
}
