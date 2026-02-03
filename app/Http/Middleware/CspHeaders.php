<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CspHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $nonce = base64_encode(random_bytes(16));

        $response = $next($request);

        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self';
             script-src 'self' https://cdn.jsdelivr.net 'nonce-{$nonce}';
             style-src 'self'
               https://cdn.jsdelivr.net
               https://fonts.googleapis.com
               https://cdnjs.cloudflare.com
               'unsafe-inline';
             font-src 'self'
               https://fonts.gstatic.com
               https://cdnjs.cloudflare.com;
             img-src 'self' data:;
             connect-src 'self';
             frame-ancestors 'self';
             base-uri 'self';
             form-action 'self';"
        );

        view()->share('cspNonce', $nonce);

        return $response;
    }
}
