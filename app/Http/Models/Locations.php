<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locations extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'city_id',
    ];

    /**
     * function to get thats the location belongs to
     *
     *
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * funcyion to get branches belongs to some location
     *
     *
     */
    public function branches()
    {
        return $this->hasMany('App\Models\Branch');
    }
}
