<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Song extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'album_id',
        'uuid',
    ];

    protected $hidden = [
        'album_id',
    ];

    // More info about this feature:
    // https://laravel.com/docs/5.0/eloquent#eager-loading
    public function Album()
    {
      return $this->belongsTo('App\Album','album_id');
    }
}
