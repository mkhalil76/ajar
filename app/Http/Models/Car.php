<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Brand;
use App\Models\CarType;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Car extends Model
{
    private $total_price;

    use SoftDeletes;

    private $car_category = [
        '',
        'عائلية',
        'صغيرة',
        'دفع رباعي'
    ];

    public function year_list()
    {
        $current_year = Date("Y");
        $last_four_year = $current_year-4;

        $years = [];
        $c= 0;
        for ($i = $last_four_year; $i < $current_year ; $i++) {
            array_push($years, $current_year-$c);
            $c++;
        }

        return implode(',', $years);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand', 'type', 'model', 'can_delivery_in_another_branch', 'provider_id' , 'category', 'picture', 'price_per_day', 'branch_id'
    ];

    protected $with = ['features', 'branch'];

    /**
     * the attributse thats should be retuurnde with user
     */
    protected $append = ['features', 'branch'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cars';

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
    public function post_new_rules()
    {
        return [
            'brand' => 'required',
            'type' => 'required',
            'model' => 'required|in:'.$this->year_list(),
            'can_delivery_in_another_branch' => 'required',
            'category' => 'required|in:1,2,3',
            'price_per_day' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'features_id' => 'required'
        ];
    }



    /**
     * new branch validation rules
     *
     * @var array
     */
    public $update_rules = [
        'brand' => 'max:50',
        'type' => 'max:50',
        'model' => 'max:50',
        'can_delivery_in_another_branch' => 'in:1,0',
        'category' => 'in:1,2,3',
        'car_id' => 'exists:cars,id',
        'branch_id' => 'required|exists:branches,id',
    ];

    /**
     * function to get category value
     *
     */
    public function getCategoryAttribute($value)
    {
        return $this->car_category[$value];
    }

    /**
     * function to get category value
     *
     */
    public function getBrandAttribute($value)
    {
        try {
            $brand = Brand::findOrFail($value);
        } catch (ModelNotFoundException $e) {
            return "";
        }
        return $brand->name;

    }

    /**
     * function to get category value
     *
     */
    public function getTypeAttribute($value)
    {
        try {
            $type = CarType::findOrFail($value);
        } catch (ModelNotFoundException $e) {
            return "";
        }
        return $type->name;
    }

    /**
     * The features that belong to the car.
     */
    public function features()
    {
        return $this->belongsToMany('App\Models\Features');
    }

    /**
     * get the list of reserviations for this car
     */
    public function reservation()
    {
        return $this->hasOne('App\Models\Reservation');
    }

    /**
     * get branch thats the car belongs to
     */
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    /**
     * function to get picture link
     */
    public function getPictureAttribute($value)
    {
        try {
            $type = CarType::findOrFail($value);
        } catch (ModelNotFoundException $e) {
            return "";
        }
        return $type->picture;
    }
}
