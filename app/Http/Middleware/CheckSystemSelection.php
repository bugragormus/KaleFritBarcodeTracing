<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSystemSelection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !session()->has('selected_system')) {
            // Eğer AJAX isteği ise 403 veya özel bir JSON dönülebilir, normalistekte yönlendirme
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'System not selected. Please select a system.', 'redirect' => route('system.selection.index')], 403);
            }
            return redirect()->route('system.selection.index');
        }

        // Frit sayfalarına girerken granilya seçilmişse engelle (veya yönlendir)
        if (auth()->check() && session('selected_system') === 'granilya') {
            // Granilya route'larına izin ver
            if (!$request->routeIs('granilya.*') && !$request->routeIs('system.selection.*') && !$request->routeIs('logout')) {
                return redirect()->route('granilya.dashboard');
            }
        }

        return $next($request);
    }
}
