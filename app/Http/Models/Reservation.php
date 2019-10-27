<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_date', 'to_date', 'status', 'total_price', 'car_id', 'user_id', 'provider_id'
    ];

    protected $with = ['user', 'car'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * function to get user associated with reserviation
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * function to get user associated with reserviation
     */
    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    /**
     * function to get branch throug the car
     */
    public function branch()
    {
        return $this->hasManyThrough('App\Models\Branch', 'App\Models\Car');
    }

    /**
     * new reserviation validation rules
     * 
     * @var array
     */
    public $new_rules = [
        'from_date' => 'required',
        'to_date' => 'required',
        'total_price' => 'required',
        'car_id' => 'required|exists:cars,id',
    ];
}
