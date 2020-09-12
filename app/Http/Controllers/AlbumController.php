<?php

namespace App\Http\Controllers;

use App\Album;
use App\Artist;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{

    use ApiResponser;

    /**
     * Return albums list
     *
     * @return @Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $exact = $request->input('exact');

        $albums = Album::when($title, function ($query, $title) use ($exact) {
            $condition = $exact ? '=' : 'like';
            $value = $exact ? $title : '%' . $title . '%';
            return $query->where('title', $condition, $value);
        })
        ->with('artist')
        ->get();

        return $this->successResponse($albums);
    }

    /**
     * Return albums list of an artist
     *
     * @return @Illuminate\Http\Response
     */
    public function indexByArtist(Request $request, $artist)
    {
        Artist::findOrFail($artist);

        $title = $request->input('title');
        $exact = $request->input('exact');

        $albums = Album::where('artist_id', $artist)
        ->when($title, function ($query, $title) use ($exact) {
            $condition = $exact ? '=' : 'like';
            $value = $exact ? $title : '%' . $title . '%';
            return $query->where('title', $condition, $value);
        })
        ->with('artist')
        ->get();

        return $this->successResponse($albums);
    }


    /**
     * Create an instance of album
     *
     * @return @Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $rules = [
        'title' => 'required|min:2|max:255',
        'artist_id' => 'required|exists:artists,id',
      ];

      $this->validate($request, $rules);

      $album = Album::create($request->all());

      return $this->successResponse($album, Response::HTTP_CREATED);
    }

    /**
     * Return specific album
     *
     * @return @Illuminate\Http\Response
     */
    public function show($album)
    {
      $album = Album::findOrFail($album);

      return $this->successResponse($album);
    }

    /**
     * Update the information of an existing album
     *
     * @return @Illuminate\Http\Response
     */
    public function update(Request $request, $album)
    {
      $rules = [
        'title' => 'required|min:2|max:255',
      ];

      $this->validate($request, $rules);

      $album = Album::findOrFail($album);

      $album->fill($request->all());

      if($album->isClean()){
        return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
      }

      $album->save();

      return $this->successResponse($album);
    }

    /**
     * Removes an existing album
     *
     * @return @Illuminate\Http\Response
     */
    public function destroy($album)
    {
      $album = Album::findOrFail($album);
      $album->delete();
      return $this->successResponse($album);
    }

  }
