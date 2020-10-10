<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'year',
        'artist_id',
        'uuid'
    ];

    protected $hidden = [
      'artist_id',
    ];

    protected $appends = ['covers'];

    // More info about this feature:
    // https://laravel.com/docs/5.0/eloquent#eager-loading
    public function Artist()
    {
        return $this->belongsTo('App\Artist','artist_id');
    }

    // More info about this feature:
    // https://laravel.com/docs/8.x/eloquent-relationships#one-to-many
    public function Songs()
    {
        return $this->hasMany('App\Song', 'album_id', 'id');
    }

    // More info about this feature:
    // https://stackoverflow.com/a/60879655/1378408
    // https://laravel.com/docs/7.x/eloquent-serialization#appending-values-to-json
    public function getCoversAttribute()
    {
        $coversFolder = "/storage/app/public/covers/";

        if(!file_exists(__DIR__."/..{$coversFolder}{$this->uuid}-original.jpg")){
            return [];
        }

        return [
            "original"  => env('APP_URL') . "{$coversFolder}{$this->uuid}-original.jpg",
            "500"       => env('APP_URL') . "{$coversFolder}{$this->uuid}-500.jpg",
            "100"       => env('APP_URL') . "{$coversFolder}{$this->uuid}-100.jpg",
        ];
    }

}
