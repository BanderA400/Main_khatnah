<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');
        $response->headers->set(
            'Content-Security-Policy',
            "frame-ancestors 'self'; base-uri 'self'; form-action 'self'",
        );

        if (app()->environment('production') && $this->isHttpsRequest($request)) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }

    private function isHttpsRequest(Request $request): bool
    {
        if ($request->isSecure()) {
            return true;
        }

        $forwardedProto = strtolower((string) $request->headers->get('X-Forwarded-Proto', ''));

        return str_starts_with($forwardedProto, 'https');
    }
}
