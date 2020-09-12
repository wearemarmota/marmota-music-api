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
    ];

    protected $hidden = [
      'artist_id',
    ];

    // More info about this feature:
    // https://laravel.com/docs/5.0/eloquent#eager-loading
    public function Artist()
    {
      return $this->belongsTo('App\Artist','artist_id');
    }
}
