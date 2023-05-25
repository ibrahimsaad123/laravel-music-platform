<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->bearerToken()) {
            throw new AuthenticationException(
                'Unauthenticated.',
                ['bearer']
            );
        }

        $user = User::where('api_token', $request->bearerToken())->first();

        if (! $user) {
            throw new AuthenticationException(
                'Unauthenticated.',
                ['bearer']
            );
        }

        return $next($request);
    }

}
