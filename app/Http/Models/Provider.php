<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Authenticatable
{
    use Notifiable;

    use SoftDeletes;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_name', 'office_name', 'commercial_no', 'admin_name', 'admin_mobile', 'device_type','password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * the attributse thats should be retuurnde with user
     */
    protected $append = ['documents', 'standerds', 'branches', 'cars'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'providers';

    /**
     * append provider documents to the provider object
     */
    protected $with = ['documents'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * the validation rules for new provider
     * 
     * @var array
     */
    public $new_provider_rules = [
        'office_name' => 'required|max:300',
        'owner_name' => 'required|max:300',
        'commercial_no' => 'required|unique:providers',
        'admin_name' => 'required|max:300',
        'admin_mobile' => 'required|unique:providers'
    ];


    /**
     * the validation rules for provider login
     * 
     * @var array
     */
    public $login_rules = [
        'admin_mobile' => 'required|exists:providers'
    ];

    /**
     * the validation rules for upload required documents
     * 
     * @var array
     */
    public $document_store_rules = [
        'commercial_log' => 'required|max:30000',
        'logo' => 'required|max:30000'
    ];

    
    /**
     * the validation rules for upload required documents
     * 
     * @var array
     */
    public $document_update_rules = [
        'commercial_log' => 'max:30000',
        'logo' => 'max:30000'
    ];

    /**
     * Get the documents record associated with the provider.
     */
    public function Documents()
    {
        return $this->hasOne('App\Models\ProviderDocuments');
    }

    /**
     * Get the standerds record associated with the provider.
     */
    public function Standerds()
    {
        return $this->hasOne('App\Models\Standard');
    }

    /**
     * Get branches records associated with provider
     */
    public function Branches()
    {
        return $this->hasMany('\App\Models\Branch');
    }

    /**
     * Get branches records associated with provider
     */
    public function Cars()
    {
        return $this->hasMany('\App\Models\Car');
    }

    public function update_rules($id) 
    {
        return [
            'office_name' => 'required|max:300',
            'owner_name' => 'required|max:300',
            'commercial_no' => 'required|unique:providers,commercial_no,'.$id,
            'admin_name' => 'max:300',
            'admin_mobile' => 'required|unique:providers,admin_mobile,'.$id
        ];
    }

    /**
     * function to get payment type related to the provider
     * 
     */
   public function Payment()
   {
       return $this->hasOne('App\Models\ProviderPayments');
   }
}
