<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'song_user';

    /**
     * Disable timestamps for this model.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'song_id', 'user_id',
    ];

}
