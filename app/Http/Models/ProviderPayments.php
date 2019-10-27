<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderPayments extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_type', 'provider_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'provider_payments';

    /**
     * get the user thats the payments associated with it
     * 
     */
    public function provider() 
    {
    	return $this->belongsTo('App\Models\Provider');
    }
}
