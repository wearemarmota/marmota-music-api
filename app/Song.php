<?php

namespace App;

use App\Favorite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Song extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'album_id',
        'uuid',
        'genre',
        'duration',
        'position',
        'position_of',
        'disk',
        'disk_of',
        'bitrate',
        'samplerate',
    ];

    protected $hidden = [
        'album_id',
    ];

    protected $appends = ['fileUri', 'isFavorited'];

    // More info about this feature:
    // https://laravel.com/docs/5.0/eloquent#eager-loading
    public function Album()
    {
      return $this->belongsTo('App\Album','album_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriters()
    {
        return $this->belongsToMany('App\User');//->withPivot('id');
    }

    /**
     * @return boolean
     */
    public function getIsFavoritedAttribute(){
        return Favorite::where([
            "user_id" => \Auth::user()->id,
            "song_id" => $this->id,
        ])->exists();
    }

    // More info about this feature:
    // https://stackoverflow.com/a/60879655/1378408
    // https://laravel.com/docs/7.x/eloquent-serialization#appending-values-to-json
    public function getFileUriAttribute()
    {
        $songsFolder = "/storage/app/public/songs/";
        $fileName = $this->uuid . ".mp3";
        return env('APP_URL') . $songsFolder . $fileName;
    }
}
