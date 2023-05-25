<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\FavoriteSongController;
use App\Http\Controllers\SingerController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserFavoriteSingerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//AUTH ROUTES
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('sendVerificationCode',[AuthController::class,'sendVerificationCode']);
Route::post('verify',[AuthController::class,'verify']);



//SINGERS ROUTES
Route::get('/singers', [SingerController::class, 'index']);
Route::get('/singers/{id}', [SingerController::class, 'show']);
Route::get('/search-singers', [SingerController::class, 'searchSingers']);





//SONGS ROUTES
Route::get('/songs', [SongController::class, 'index']);
Route::get('/songs/{id}', [SongController::class, 'show']);
Route::get('/songs/search',[SongController::class, 'searchSongs'] );

Route::group(['middleware' => ['auth:sanctum', 'Check_Singer_And_Admin']], function () {

    Route::post('/create-song', [SongController::class, 'store']);


    Route::group(['middleware' => 'Check_If_Singer_Has_This_Song_And_Admin'],function(){
        Route::post('/update-song/{id}', [SongController::class, 'update']);
        Route::delete('/delete-song/{id}', [SongController::class, 'destroy']);
    });

});


//ALBUMS ROUTES
Route::get('/albums', [AlbumController::class, 'index']);
Route::get('/albums/{album}', [AlbumController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum', 'Check_Singer_And_Admin']], function () {

    Route::post('/create-albums', [AlbumController::class, 'create']);

    Route::post('/albums/{id}/add-songs', [AlbumController::class, 'AddSongsToNewAlbum']);

    Route::group(['middleware' => 'Check_If_Singer_Has_The_Album_And_Admin'],function(){
        Route::post('/albums/{id}/add-songs', [AlbumController::class, 'AddSongsToTheAlbum']);
        Route::post('/update-albums/{album}', [AlbumController::class, 'update']);
        Route::delete('/delete-albums/{album}', [AlbumController::class, 'destroy']);

        //راوت لفانكشن تقوم بحذف الاغاني من الالبوم
        Route::delete('/albums/{id}/delete-songs-from-album', [AlbumController::class, 'DeleteSongFromAlbum']);
    });

});


//FAVORITE SONGS
Route::group(['middleware' => 'auth:sanctum'],function () {
    Route::get('favorite-songs', [FavoriteSongController::class, 'index']);
    Route::post('favorite-songs', [FavoriteSongController::class, 'store']);
    //Route::delete('/{user_id}/{id}', [FavoriteSongController::class, 'destroy']);
    Route::delete('favorite-songs/{song_id}', [FavoriteSongController::class, 'destroy']);
});
/*
Route::prefix('favorite-songs')->group(function () {
    Route::get('/', [FavoriteSongController::class, 'index']);
    Route::post('/', [FavoriteSongController::class, 'store']);
    Route::delete('/{id}', [FavoriteSongController::class, 'destroy']);
});*/


//FAVORITE SINGERS
// عرض المغنبن المفضلين لأي مستخدم
Route::get('/users/{user}/favorite-singers',[UserFavoriteSingerController::class,'showFavoriteSingers']);

// عرض المغنيين المفضلين للمستخدم الحالي
Route::get('/favorite-singers',[UserFavoriteSingerController::class,'index']);

// عرض المغني المفضل للمستخدم بالمعرف
Route::get('/favorite-singers/{singerId}',[UserFavoriteSingerController::class,'showFavoriteSinger']);

// إضافة مغني للمفضلين للمستخدم الحالي
Route::post('/favorite-singers',[UserFavoriteSingerController::class,'store']);

// حذف المغني من المفضلين للمستخدم الحالي
Route::delete('/favorite-singers/{singerId}',[UserFavoriteSingerController::class,'destroy']);




