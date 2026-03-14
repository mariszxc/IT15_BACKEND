<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventStaleApiCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->is('api/*')) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('Surrogate-Control', 'no-store');
        $response->headers->set('Vary', 'Authorization, Accept, Origin');
        $response->headers->set('ETag', '');
        $response->headers->remove('Last-Modified');

        if ($request->is('api/dashboard')) {
            $response->headers->set('Clear-Site-Data', '"cache"');
        }

        return $response;
    }
}
