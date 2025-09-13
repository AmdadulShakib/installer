<?php

namespace amdadulshakib\installer\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        $isInstalled = file_exists(storage_path('installed.lock'));

        if (!$isInstalled && !$request->is('install*')) {
            return redirect('/install');
        }

        if ($isInstalled && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}
