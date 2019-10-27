<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'model', 'picture', 'brand_id'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     *
     */
    protected $table = 'car_types';

    /**
     * get the languages for service provider language
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
}
