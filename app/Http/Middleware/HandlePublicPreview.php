<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandlePublicPreview
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->boolean('preview')) {
            $request->session()->put('public_preview', true);
        }

        if ($request->query('edit') === '1') {
            $request->session()->forget('public_preview');
        }

        return $next($request);
    }
}
