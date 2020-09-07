<?php

namespace App\Http\Controllers;

use App\Album;
use App\Author;
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
        $name = $request->input('name');
        $exact = $request->input('exact');

        $albums = Album::when($name, function ($query, $name) use ($exact) {
            $condition = $exact ? '=' : 'like';
            $value = $exact ? $name : '%' . $name . '%';
            return $query->where('name', $condition, $value);
        })
        ->with('author')
        ->get();

        return $this->successResponse($albums);
    }

    /**
     * Return albums list of an author
     *
     * @return @Illuminate\Http\Response
     */
    public function indexByAuthor(Request $request, $author)
    {
        Author::findOrFail($author);

        $name = $request->input('name');
        $exact = $request->input('exact');

        $albums = Album::where('author_id', $author)
        ->when($name, function ($query, $name) use ($exact) {
            $condition = $exact ? '=' : 'like';
            $value = $exact ? $name : '%' . $name . '%';
            return $query->where('name', $condition, $value);
        })
        ->with('author')
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
        'name' => 'required|min:2|max:255',
        'author_id' => 'required|exists:authors,id',
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
        'name' => 'required|min:2|max:255',
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
