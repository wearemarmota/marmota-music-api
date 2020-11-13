<?php

namespace App\Http\Controllers;

use App\Song;
use App\Album;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use wapmorgan\Mp3Info\Mp3Info;

class SongController extends Controller
{

    use ApiResponser;

    const SONGS_FOLDER = '../storage/app/public/songs';

    /**
     * Return songs list
     *
     * @return @Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $songs = Song::with('album', 'album.artist')
        ->orderBy('position')
        ->get();

      return $this->successResponse($songs);
    }

    /**
     * Return songs list of an artist
     *
     * @return @Illuminate\Http\Response
     */
    public function indexByAlbum(Request $request, $album)
    {
      Album::findOrFail($album);

      $songs = Song::where('album_id', $album)
        ->with('album', 'album.artist')
        ->orderBy('position')
        ->get();

      return $this->successResponse($songs);
    }


    /**
     * Create an instance of song
     *
     * @return @Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required|min:2|max:255',
            'album_id' => 'required|exists:albums,id',
            'song' => 'required|mimes:mp3|max:30000'
        ];

        $this->validate($request, $rules);

        $uuid = md5(uniqid(null, true));
        $extension = $request->file('song')->getClientOriginalExtension();
        $fileName = "{$uuid}.{$extension}";

        $mp3Data = new Mp3Info($request->file('song'), true);

        // ToDo: Check if the uuid already exists in DB.

        $position = 0;

        if(isset($mp3Data->tags['track'])){
            $position = $mp3Data->tags['track'];
        }

        $extraData = [
            'uuid' => $uuid,
            'duration' => $mp3Data->duration,
            'position' => $position,
            'bitrate' => $mp3Data->bitRate,
            'samplerate' => $mp3Data->sampleRate,
        ];

        if (strpos($extraData['position'], '/') !== false) {
            $positionParts = explode('/', $extraData['position']);
            $extraData['position'] = $positionParts[0];
        }

        $song = Song::create(array_merge($request->all(), $extraData));

        $request->file('song')->move(self::SONGS_FOLDER, $fileName);

        return $this->successResponse($song, Response::HTTP_CREATED);
    }

    /**
     * Return specific song
     *
     * @return @Illuminate\Http\Response
     */
    public function show($song)
    {
      $song = Song::findOrFail($song)->with('album', 'album.artist')->get();

      return $this->successResponse($song);
    }

    /**
     * Update the information of an existing song
     *
     * @return @Illuminate\Http\Response
     */
    public function update(Request $request, $song)
    {
      $rules = [
        'title' => 'required|min:1|max:255',
      ];

      $this->validate($request, $rules);

      $song = Song::findOrFail($song);

      $song->fill($request->all());

      if($song->isClean()){
        return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
      }

      $song->save();

      return $this->successResponse($song);
    }

    /**
     * Removes an existing song
     *
     * @return @Illuminate\Http\Response
     */
    public function destroy($song)
    {
      $song = Song::findOrFail($song);
      unlink(self::SONGS_FOLDER.'/'.$song->uuid.'.mp3');
      $song->delete();
      return $this->successResponse($song);
    }

  }
