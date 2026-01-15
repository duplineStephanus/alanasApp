<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;

class EnsureGuestCartToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            $token = $request->cookie('cart_token');

            if (!$token) {
                $token = Str::uuid()->toString();
                $cookie = cookie('cart_token', $token, 525600); // 1 year
                return $next($request)->withCookie($cookie);
            }
        }

        return $next($request);
    }
}