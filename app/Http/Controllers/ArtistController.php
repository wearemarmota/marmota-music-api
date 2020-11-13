<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\ImageManagerStatic as Image;

class ArtistController extends Controller
{

    use ApiResponser;

    const ARTISTS_IMAGES_FOLDER = '../storage/app/public/artists';

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
            'image' => 'mimes:jpeg,jpg,png|max:10240',
        ];

        $this->validate($request, $rules);

        // ToDo: Check if the uuid already exists in DB.

        $uuid = md5(uniqid(null, true));

        if($request->hasFile('image') && $request->file('image')->isValid()){
            Image::make($request->file('image')->path())
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$uuid}-original.jpg")
                ->fit(500)
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$uuid}-500.jpg")
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$uuid}-500.webp")
                ->fit(100)
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$uuid}-100.jpg")
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$uuid}-100.webp");
        }

        $artist = Artist::create(array_merge($request->all(), [
            'uuid' => $uuid,
        ]));

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
            'name' => 'min:2|max:255',
            'image' => 'mimes:jpeg,jpg,png|max:10240',
        ];

        $this->validate($request, $rules);

        $artist = Artist::findOrFail($artist);

        $artist->fill($request->all());

        if($artist->isClean() && !$request->hasFile('image')){
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            Image::make($request->file('image')->path())
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$artist->uuid}-original.jpg")
                ->fit(500)
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$artist->uuid}-500.jpg")
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$artist->uuid}-500.webp")
                ->fit(100)
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$artist->uuid}-100.jpg")
                ->save(self::ARTISTS_IMAGES_FOLDER."/{$artist->uuid}-100.webp");
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
