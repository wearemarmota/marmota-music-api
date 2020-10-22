<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArtistController extends Controller
{

    use ApiResponser;

    /**
     * Return artists list
     *
     * @return @Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rules = [
            'offset'    => 'integer|filled|min:0',
            'limit'     => 'integer|filled|min:1|max:999',
            'sortBy'    => 'filled|in:id,name,created_at,updated_at',
            'orderBy'   => 'string|filled|in:asc,desc',
        ];

        $this->validate($request, $rules);

        $name = $request->input('name');
        $exact = $request->input('exact');

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sortBy = $request->input('sortBy', 'created_at');
        $orderBy = $request->input('orderBy', 'desc');

        $artists = Artist::when($name, function ($query, $name) use ($exact) {
            $condition = $exact ? '=' : 'like';
            $value = $exact ? $name : '%' . $name . '%';
            return $query->where('name', $condition, $value);
        })
        ->offset($offset)
        ->limit($limit)
        ->orderBy($sortBy, $orderBy)
        ->get();

        return $this->successResponse($artists);
    }

    /**
     * Create an instance of artists
     *
     * @return @Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|min:2|max:255',
        ];

        $this->validate($request, $rules);

        // ToDo: Check if the uuid already exists in DB.

        $extraData = ['uuid' => md5(uniqid(null, true))];

        $artist = Artist::create(array_merge($request->all(), $extraData));

        return $this->successResponse($artist, Response::HTTP_CREATED);
    }

    /**
     * Return specific artist
     *
     * @return @Illuminate\Http\Response
     */
    public function show($artist)
    {
      $artist = Artist::findOrFail($artist);

      return $this->successResponse($artist);
    }

    /**
     * Update the information of an existing artist
     *
     * @return @Illuminate\Http\Response
     */
    public function update(Request $request, $artist)
    {
      $rules = [
        'name' => 'required|min:2|max:255',
      ];

      $this->validate($request, $rules);

      $artist = Artist::findOrFail($artist);

      $artist->fill($request->all());

      if($artist->isClean()){
        return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
      }

      $artist->save();

      return $this->successResponse($artist);
    }

    /**
     * Removes an existing artist
     *
     * @return @Illuminate\Http\Response
     */
    public function destroy($artist)
    {
      $artist = Artist::findOrFail($artist);
      $artist->delete();
      return $this->successResponse($artist);
    }

  }
