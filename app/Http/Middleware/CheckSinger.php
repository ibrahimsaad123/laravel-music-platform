<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;


class CheckSingerAndAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->bearerToken()) {
            throw new AuthenticationException(
                'Unauthenticated.',
                ['bearer']
            );
        }

        if (! auth()->guard('sanctum')->check()) {
            throw new AuthenticationException(
                'Unauthenticated.',
                ['bearer']
            );
        }
        $user = $request->user();

        if ( $user -> is_admin )
        {
            return $next($request);
        }


        if ($user && $user->person_type !== 'singer') {
            return response()->json(['message' => 'You are not authorized to access this resource'], 403);
        }
        return $next($request);
    }
}
