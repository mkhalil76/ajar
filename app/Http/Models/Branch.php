<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\City;
use App\Models\Locations;

class Branch extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city', 'location', 'branch_mobile', 'location_on_map', 'provider_id', 'location_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branches';

    protected $with = ['provider'];
    /**
     * Get the user that owns the branch.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * new branch validation rules
     *
     * @var array
     */
    public $post_new_rules = [
        'city' => 'required|exists:cities,id',
        'branch_mobile' => 'required|unique:branches',
        'location_id' => 'required|exists:locations,id'
    ];

    /**
     * new branch validation rules
     *
     * @var array
     */
    public function update_rules($id)
    {
        return [
            'city' => 'exists:cities,id',
            'branch_mobile' => 'unique:branches,branch_mobile,'.$id,
            'branch_id' => 'exists:branches,id',
            'location_id' => 'exists:locations,id'
        ];
    }

    /**
     * function to ge the city thats the
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }


    /**
     * function to get city name
     */
    public function getCityAttribute($value)
    {
        $city = City::where('id', '=', $value)->first();
        if (!empty($city)) {
            return $city->name;
        } else {
            return "";
        }

    }

    /**
     * function get location name attribute
     *
     * @param int $location_id
     *
     */
    public function getLocationIdAttribute($location_id)
    {
        $location = Locations::where('id', '=', $location_id)->first();
        if (!empty($location)) {
            return $location->name;
        } else {
            return "";
        }
    }
}
