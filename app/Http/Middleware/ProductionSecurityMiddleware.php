<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductionSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Production ortamında güvenlik kontrolleri
        if (App::environment('production')) {
            // HTTPS zorunluluğu
            if (!$request->secure() && !$request->is('health')) {
                return redirect()->secure($request->getRequestUri());
            }

            // Rate limiting
            if ($request->is('api/*')) {
                // API rate limiting
                if (!cache()->has('api_requests_' . $request->ip())) {
                    cache()->put('api_requests_' . $request->ip(), 1, 60);
                } else {
                    $requests = cache()->get('api_requests_' . $request->ip());
                    if ($requests > 100) { // 1 dakikada max 100 request
                        return response()->json(['error' => 'Rate limit exceeded'], 429);
                    }
                    cache()->increment('api_requests_' . $request->ip());
                }
            }

            // User agent kontrolü
            $userAgent = $request->header('User-Agent');
            if (empty($userAgent) || strlen($userAgent) > 500) {
                return response()->json(['error' => 'Invalid request'], 400);
            }

            // Request size kontrolü
            if ($request->header('Content-Length') > 10485760) { // 10MB
                return response()->json(['error' => 'Request too large'], 413);
            }
        }

        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
} 