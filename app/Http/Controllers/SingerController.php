<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SingerController extends Controller
{



    public function index()
    {
        $singers = User::where('person_type', 'singer')->get();

        return response()->json($singers);
    }




    public function show($id)
    {
        $singer = User::where('person_type', 'singer')->findOrFail($id);

        return response()->json($singer);
    }



    public function searchSingers(Request $request)
    {
        $keyword = $request->input('keyword');

        $singers = User::where('person_type', 'singer')
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        return response()->json($singers);
    }



}
