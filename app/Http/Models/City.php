<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * function to get list of branches belongs
     */
    public function branches()
    {
        return $this->hasMany('App\Models\Branch', 'city');
    }

    /**
     * function to get locations thats associated with city
     *
     */
    public function location()
    {
        return $this->hasMany('App\Models\Locations');
    }
}
