<?php

namespace App\Http\Middleware;

use App\Models\Songs;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfSingerHasThisSongAndAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $songId = $request->route('id'); // افتراض أن المعرف في الراوت هو "id"
        $user = $request->user();


        if ( $user -> is_admin )
        {
            return $next($request);
        }


        $song = Songs::findOrFail($songId);

        if ($user->id !== $song->singer_id) {
            return response()->json(['message' => 'You are not authorized to access this resource'], 403);
        }

        return $next($request);
    }
}
