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
        'name',
        'year',
        'author_id',
    ];

    protected $hidden = [
      'author_id',
    ];

    // More info about this feature:
    // https://laravel.com/docs/5.0/eloquent#eager-loading
    public function Author()
    {
      return $this->belongsTo('App\Author','author_id');
    }
}
