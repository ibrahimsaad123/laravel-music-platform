<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Songs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class SongController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $songs = Songs::all();
        return response()->json(['songs' => $songs]);
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $song = Songs::findOrFail($id);
        return response()->json(['song' => $song]);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:50',
            //'artist' => 'required|string|max:50',
            'album_id' => 'string|max:50',
            'song_file' => 'required|mimes:mp3,wav|max:10240', // 10MB maximum file size
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $song = new Songs();
        $song->title = $request->title;
        $song->description = $request->description;
        $song->album_id = $request->album_id;
        $user = $request->user();
        $song->singer_id = $user->id;
        $song->artist = $user->name;

        // Save song file to storage
        $path = $request->file('song_file')->store('public/songs');
        $song->song_file = $path;

        $song->save();

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $song = Songs::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:50',
            'description' => 'nullable',
            //'artist' => 'required|string|max:50',
            'album_id' => 'nullable|exists:albums,id',
            //'album_id' => 'string|max:50',
            'song_file' => 'mimes:mp3,wav|max:10240', // 10MB maximum file size
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $song->title = $request->title;
        $song->description = $request->description;
        //$song->artist = $request->artist;
        $song->album_id = $request->album_id;

        // If a new song file is uploaded, delete the old one and save the new one
        if ($request->hasFile('song_file')) {
            Storage::delete($song->song_file);
            $path = $request->file('song_file')->store('public/songs');
            $song->song_file = $path;
        }

            $song->save();
            return response()->json(['message' => 'Song updated successfully', 'song' => $song], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $song = Songs::findOrFail($id);

        // Delete song file from storage
        Storage::delete($song->song_file);

        $song->delete();

        return response()->json(['message' => 'Song deleted successfully'], 200);
    }


    /**
     * فانكشن للبحث عن الاغاني
     */
    public function searchSongs(Request $request)
    {
        $keyword = $request->input('keyword');

        $songs = Songs::where('title', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($songs);
    }




/*
    public function addSongsToAlbum(Request $request, $albumId)
    {
        // التحقق من وجود الألبوم
        $album = Album::findOrFail($albumId);

    }
*/
}
