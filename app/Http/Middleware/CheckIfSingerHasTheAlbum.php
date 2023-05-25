<?php

namespace App\Http\Middleware;

use App\Models\Album;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfSingerHasTheAlbumAndAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   /* public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user->id !== $user->albums()->singer_id)
        {
            return response()->json(['message' => 'You are not authorized to access this resource'], 403);
        }
        return $next($request);
    }*/
    public function handle(Request $request, Closure $next)
    {
        $albumId = $request->route('album'); // افتراض أن المعرف في الراوت هو "album"
        $user = $request->user();

        if ( $user -> is_admin )
        {
            return $next($request);
        }

        $album = Album::findOrFail($albumId);

        if ($user->id !== $album->singer_id) {
            return response()->json(['message' => 'You are not authorized to access this resource'], 403);
        }

        return $next($request);
    }
}
