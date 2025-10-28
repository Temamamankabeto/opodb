<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Basic security headers
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Content Security Policy (CSP)
        // conver to noncesional CSP
        // This is a basic CSP that allows resources from the same origin and specific CDNs
        // Adjust the sources as needed for your application
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com; " .
            "font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com; " .
            "img-src 'self' data: https://osdavc.com; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none';"
        );

        return $response;
    }
}
