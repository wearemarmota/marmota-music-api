<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Album;
use App\Song;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class SearchController extends Controller
{

    use ApiResponser;

    /**
     * Return search results
     *
     * @return @Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $rules = [
            'term' => 'string|required|min:3|max:40',
        ];

        $this->validate($request, $rules);

        $term = $request->input('term');

        $artists = Artist::where('name', 'like', '%' . $term . '%');
        $albums = Album::where('title', 'like', '%' . $term . '%')->with("artist");
        $songs = Song::where('title', 'like', '%' . $term . '%');

        return $this->successResponse([
            "artists"   => $artists->get(),
            "albums"    => $albums->get(),
            "songs"     => $songs->get(),
        ]);
    }

  }
