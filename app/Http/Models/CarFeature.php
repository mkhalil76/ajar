<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarFeature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id','features_id'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     *
     */
    protected $table = 'car_features';

    /**
     * get the languages for service provider language
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    /**
     * get the service provider for service provider language
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function features()
    {
        return $this->belongsTo('App\Models\Features');
    }
}
