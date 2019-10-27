<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standard extends Model
{
    use SoftDeletes;

    /**
     * type of insurance
     * 
     * @var array
     */
    private $insurance = [
        '',
        'شامل' ,
        'قسط تأمين' ,
        'ضد الغير '
    ];

    /**
     * type of license
     * 
     * @var array
     */
    protected $licens_type = [
        '',
        'سعودية',
        'دول مجلس التعاون' ,
        'دولية'
    ]; 

    /**
     * type of license
     * 
     * @var array
     */
    protected $free_kilo = [
        '',
        'مفتوح',
        '200' ,
        '400'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'insurance', 'licens_type', 'from_age', 'to_age', 'free_kilo', 'provider_id'
    ];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'standards';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the user that owns the standerds.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }

    /**
     * post new standerd validation
     */
    public $post_new_rules = [
        'insurance' => 'required|in:1,2,3',
        'licens_type' => 'required|in:1,2,4',
        'from_age' => 'required',
        'to_age' => 'required',
        'free_kilo' => 'required'
    ];

    /**
     * post new standerd validation
     */
    public $update_rules = [
        'insurance' => 'in:1,2,3',
        'licens_type' => 'in:1,2,4',
        'standerd_id' => 'required'
    ];

    /**
     * function to get Insurance value
     * 
     */
    public function getInsuranceAttribute($value)
    {   
        return $this->insurance[$value];
    }

    /**
     * function to get licens_type
     * 
     */
    public function getLicensTypeAttribute($value)
    {   
        return $this->licens_type[$value];
    }

    /**
     * function to get free kilo
     * 
     */
    public function getFreeKilpAttribute($value)
    {   
        return $this->free_kilo[$value];
    }
}
