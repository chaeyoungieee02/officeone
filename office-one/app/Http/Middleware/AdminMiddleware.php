<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Only allow admin users through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        if (!auth()->user()->isAdmin()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Unauthorized. Admin access only.');
        }

        return $next($request);
    }
}
