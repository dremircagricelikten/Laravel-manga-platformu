<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInstallation
{
    public function handle(Request $request, Closure $next)
    {
        // Skip check for install routes and health check
        if ($request->is('install*') || $request->is('up')) {
            return $next($request);
        }

        // Check if installed
        if (!file_exists(storage_path('installed'))) {
            return redirect('/install');
        }

        return $next($request);
    }
}
