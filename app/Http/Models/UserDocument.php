<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDocument extends Model
{
    use SoftDeletes;

    /**
     * Get the user that owns the documents.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'national_id_image', 'driving_license_image', 'job_card_image', 'user_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_documents';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * function to get national_id_image full path 
     * 
     */
    public function getNationalIdImageAttribute($value)
    {
        return asset('/public/assets/upload/'.$value);
    }

    /**
     * function to get driving_License_image full path 
     * 
     */
    public function getDrivingLicenseImageAttribute($value)
    {
        return asset('/public/assets/upload/'.$value);
    }

    /**
     * function to get driving_License_image full path 
     * 
     */
    public function getJobCardImageAttribute($value)
    {
        return asset('/public/assets/upload/'.$value);
    }
}
