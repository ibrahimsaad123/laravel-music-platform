<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Songs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;


class AlbumController extends Controller
{
    public $array;

    public function __construct()
    {
        $this->array = collect();
    }
    public function addToArray ($id)
    {
        $this->array->push($id);
    }
    public function removeFromArray ($id)
    {
        $index = $this->array->search($id);

        if ($index !== false) {
            $this->array->forget($index);
        }

    }
    public function AddSongsToNewAlbum($id,Collection $idArray)
    {

        if ($idArray->count() < 3 )
        {
            return response()->json(['errors' => 'the min is 3 songs or more'],422 );
        }
        foreach ($idArray as $idSong) {
            $song = Songs::findOrFail($idSong);
            $song->album_id = $id;
        }
        $this->array = collect();
        return response()->json(['message' => 'The songs have been added successfully to the album ']);
    }

    public function AddSongsToTheAlbum($id,Collection $idArray)
    {
        $album = Album::findOrFail($id);
        foreach ($idArray as $idSong) {
            $song = Songs::findOrFail($idSong);
            if ($album->id == $song->album_id )
            {
                continue;
            }
            $song->album_id = $id;
        }
        $this->array = collect();
        return response()->json(['message' => 'The songs have been added successfully to the album ']);
    }

    public function DeleteSongFromAlbum($id,Collection $idArray)
    {
        $album = Album::findOrFail($id);
        foreach ($idArray as $idSong) {
            $song = Songs::findOrFail($idSong);
            if ($album->id == $song->album_id )
            {
                $song->album_id = null ;
            }
        }
        $this->array = collect();
        return response()->json(['message' => 'The songs have been deleted successfully from the album ']);
    }

    //نفس عمل الشرط في الالبوم السابق و لكن بطريقة الميدلوير
    /*
class CheckSongInAlbum
{
    public function handle($request, Closure $next)
    {
        $albumId = $request->route('id');
        $songIds = $request->input('song_ids', []);
        foreach ($songIds as $songId) {
            $song = Songs::where('album_id', $albumId)->find($songId);
            if (!$song) {
                return response()->json(['error' => 'Song not found in the album'], 404);
            }
        }
        return $next($request);
    }
}*/
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = Album::all();
        return response()->json($albums);
    }


    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return response()->json($album);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'release_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $album = Album::create($request->all());
        $user = $request->user();
        $album->singer_id = $user->id;
        return response()->json($album, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        /*$user = $request->user();
        if ($user->id !== $album->singer_id)
        {
            return response()->json(['message' => 'You are not authorized to access this resource'], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'release_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $album->update($request->all());
        return response()->json($album);
    }

    /**
     * Remove the specified resource from storage.
     */
   /* public function destroy(Album $album)
    {
        $album->delete();
        return response()->json(null, 204);
    }*/
    public function destroy(Album $album)
    {
        $album->songs()->update(['album_id' => null]);
        $album->delete();

        return response()->json(null, 204);
    }

}
