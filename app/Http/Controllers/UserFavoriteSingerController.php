<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SingerController;
use App\Models\User;
use App\Models\UserFavoriteSinger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserFavoriteSingerController extends Controller
{
    public $singer;

    public function __construct(SingerController $singer)
    {
        $this->singer = $singer;
    }

    /**
      * هذا الفانكشن لكي يقوم اي مستخدم بعرض المغنببن المفضلين لاي مستخدم
     */
    public function showFavoriteSingers(User $user)
    {
        $favoriteSingers = $user->favoriteSingers;
        return response()->json($favoriteSingers);
    }


    /**
     * هذا الفانكشن لكي اقوم باستعراض المغنيين المفضلين للمستخدم الحالي
     */
    public function index()
    {
        $userFavoriteSingers = UserFavoriteSinger::where('user_id', Auth::id())->get();

        return response()->json($userFavoriteSingers);
    }


    /**
    هذا فانكشن بعرض المغني المحدد بالمعرف  $singerId
     */
    public function showFavoriteSinger( $singerId)
    {
       // $favoriteSinger  = UserFavoriteSinger::where('singer_id', $singerId)->get();
        //$favoriteSinger = $user->favoriteSingers()->find($singerId);

       /* if (!$favoriteSinger) {
            return response()->json(['message' => 'Favorite singer not found'], 404);
        }*/

       // return response()->json($favoriteSinger);
        return $this->singer->show($singerId);
    }

    public function store(Request $request)
    {
        $request->validate([
            'singer_id' => 'required|exists:users,id',
        ]);
        $userFavoriteSinger = new UserFavoriteSinger;
        $userFavoriteSinger->user_id = Auth::id();
        $userFavoriteSinger->singer_id = $request->input('singer_id');
        $userFavoriteSinger->save();

        return response()->json($userFavoriteSinger, 201);
    }


    public function destroy($singerId)
    {
        $userFavoriteSinger = UserFavoriteSinger::where('user_id', Auth::id())->where('singer_id', $singerId)->firstOrFail();
        $userFavoriteSinger->delete();

        return response()->json(null, 204);
    }

}
