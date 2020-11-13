<?php

namespace App\Http\Controllers;

use App\Album;
use App\Artist;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class AlbumController extends Controller
{

    use ApiResponser;

    const COVERS_FOLDER = '../storage/app/public/covers';

    /**
     * Return albums list
     *
     * @return @Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $rules = [
            'offset'    => 'integer|filled|min:0',
            'limit'     => 'integer|filled|min:1|max:999',
            'sortBy'    => 'filled|in:id,title,artist_id,created_at,updated_at',
            'orderBy'   => 'string|filled|in:asc,desc',
        ];

        $this->validate($request, $rules);

        $title = $request->input('title');
        $exact = $request->input('exact');

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sortBy = $request->input('sortBy', 'created_at');
        $orderBy = $request->input('orderBy', 'desc');
        $shuffle = $request->input('shuffle');

        $albums = Album::when($title, function ($query, $title) use ($exact) {
            $condition = $exact ? '=' : 'like';
            $value = $exact ? $title : '%' . $title . '%';
            return $query->where('title', $condition, $value);
        })
        ->with('artist')
        ->offset($offset)
        ->limit($limit);

        if($shuffle){
            $albums->inRandomOrder();
        }else{
            $albums->orderBy($sortBy, $orderBy);
        }

        return $this->successResponse($albums->get());
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
            'cover' => 'mimes:jpeg,jpg,png|max:10240',
        ];

        $this->validate($request, $rules);

        // ToDo: Check if the uuid already exists in DB.

        $uuid = md5(uniqid(null, true));

        if($request->hasFile('cover') && $request->file('cover')->isValid()){
            Image::make($request->file('cover')->path())
                ->save(self::COVERS_FOLDER."/{$uuid}-original.jpg")
                ->fit(500)
                ->save(self::COVERS_FOLDER."/{$uuid}-500.jpg")
                ->save(self::COVERS_FOLDER."/{$uuid}-500.webp")
                ->fit(100)
                ->save(self::COVERS_FOLDER."/{$uuid}-100.jpg")
                ->save(self::COVERS_FOLDER."/{$uuid}-100.webp");
        }

        $album = Album::create(array_merge($request->all(), [
            'uuid' => $uuid
        ]));

        return $this->successResponse($album, Response::HTTP_CREATED);
    }

    /**
     * Return specific album
     *
     * @return @Illuminate\Http\Response
     */
    public function show($album)
    {
      $album = Album::with('artist')->with('songs')->findOrFail($album);

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
            'title' => 'min:2|max:255',
            'artist_id' => 'exists:artists,id',
            'cover' => 'mimes:jpeg,jpg,png|max:10240',
        ];

        // var_dump($request->all());
        $this->validate($request, $rules);

        $album = Album::findOrFail($album);

        $album->fill($request->all());

        if($album->isClean() && !$request->hasFile('cover')){
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if($request->hasFile('cover') && $request->file('cover')->isValid()){
            Image::make($request->file('cover')->path())
                ->save(self::COVERS_FOLDER."/{$album->uuid}-original.jpg")
                ->fit(500)
                ->save(self::COVERS_FOLDER."/{$album->uuid}-500.jpg")
                ->save(self::COVERS_FOLDER."/{$album->uuid}-500.webp")
                ->fit(100)
                ->save(self::COVERS_FOLDER."/{$album->uuid}-100.jpg")
                ->save(self::COVERS_FOLDER."/{$album->uuid}-100.webp");
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
