<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderDocuments extends Model
{
    use SoftDeletes;

    /**
     * Get the user that owns the documents.
     */
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commercial_log', 'logo', 'provider_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provider_documents';

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
    public function getCommercialLogAttribute($value)
    {
        return asset('/public/assets/upload/'.$value);
    }

    /**
     * function to get driving_License_image full path 
     * 
     */
    public function getLogoAttribute($value)
    {
        return asset('/public/assets/upload/'.$value);
    }
}
