<?php

namespace App\Http\Controllers;

use App\Models\FavoriteSong;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FavoriteSongController extends Controller
{

    /**
      * هذا الفانكشن لكي يقوم اي مستخدم بعرض الاغاني المفضلة لاي مستخدم
     */
    public function showFavoriteSongs(User $user)
    {
        $favoriteSongs = $user->favoriteSongs;
        return response()->json($favoriteSongs);
    }
    /**
     * عرض الأغاني المفضلة للمستخدم الحالي الموجود
     */
    public function index()
    {
        $favoriteSongs = FavoriteSong::where('user_id', Auth::id())->get();

        return response()->json($favoriteSongs);
    }

    /**
     * إضافة أغنية إلى الأغاني المفضلة لمستخدم محدد
     *
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'song_id' => 'required|exists:songs,id',
        ]);

        $favoriteSong = new FavoriteSong();
        $favoriteSong->user_id = Auth::id();
        $favoriteSong->song_id = $request->input('song_id');
        $favoriteSong->save();

        return response()->json();
    }

    /**
     * حذف أغنية من الأغاني المفضلة لمستخدم محدد
     *
     * @param int $userId
     * @param int $songId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($songId)
    {
        $favoriteSong = FavoriteSong::where('user_id', Auth::id())->where('song_id', $songId)->firstOrFail();
        $favoriteSong->delete();

        return response()->json();
    }
}

