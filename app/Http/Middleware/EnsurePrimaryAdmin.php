<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrimaryAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isPrimaryAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Only the CMS manager can manage admin users.');
        }

        return $next($request);
    }
}
