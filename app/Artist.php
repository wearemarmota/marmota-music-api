<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'uuid',
    ];

    protected $appends = ['images'];


    // More info about this feature:
    // https://stackoverflow.com/a/60879655/1378408
    // https://laravel.com/docs/7.x/eloquent-serialization#appending-values-to-json
    public function getImagesAttribute()
    {
        $imagesFolder = "/storage/app/public/artists/";

        if(!file_exists(__DIR__."/..{$imagesFolder}{$this->uuid}-original.jpg")){
            return [];
        }

        return [
            "original"  => env('APP_URL') . "{$imagesFolder}{$this->uuid}-original.jpg",
            "500"       => env('APP_URL') . "{$imagesFolder}{$this->uuid}-500.webp",
            "100"       => env('APP_URL') . "{$imagesFolder}{$this->uuid}-100.webp",
        ];
    }

}
