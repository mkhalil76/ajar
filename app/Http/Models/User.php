<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
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
        'name', 'email', 'password', 'national_id', 'age', 'mobile', 'device_type', 'user_type','activation_code', 'profile_pic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * the attributse thats should be retuurnde with user
     */
    protected $append = ['documents', 'payment', 'reservation'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * the post new user validation rules
     *
     * @return array
     */
    public  $new_user_rules = [
        'mobile' => 'required|unique:users',
        'email' => 'required|unique:users',
        'national_id' => 'required|unique:users',
        'age' => 'required',
        'device_type' => 'required|in:1,2',
        'user_type' => 'required|in:1,2'
    ];

    /**
     * validation rules for update user
     *
     */
    public function update_rules($user_id)
    {
        return [
            'mobile' => 'unique:users,mobile,'.$user_id,
            'email' => 'unique:users,email,'.$user_id,
            'national_id' => 'unique:users,national_id,'.$user_id,
            //'profile_pic' => 'Image|mimes:jpeg,bmp,png,jpg',
        ];
    }

    /**
     * user login rules
     */
    public $login_rules = [
        'mobile' => 'required|exists:users,mobile'
    ];

    /**
     * update user validation
     */
    public function updateRules($user_id)
    {
        return [
            'mobile' => 'unique:users,'.$user_id,
            'email' => 'unique:users,'.$user_id,
            'national_id' => 'unique:users,'.$user_id,
            'profile_pic' => 'image|mimes:jpeg,jpg,png'
        ];
    }


    public $document_store_rules = [
        'national_id_image' => 'required|max:30000',
        'driving_license_image' => 'required|max:30000',
        'job_card_image' => 'required|max:30000',
    ];

    /**
     * Get the documents record associated with the user.
     */
    public function Documents()
    {
        return $this->hasOne('App\Models\UserDocument');
    }

    /**
     * function to get list of reserviations for this user
     */
    public function reservation()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    /**
     * Get the payment record associated with the user.
     */
    public function Payment()
    {
        return $this->hasOne('App\Models\Payment');
    }

    /**
     * function to get profile picture full path
     *
     *
     * @param Integer $key
     */
    public function getProfilePicAttribute($key)
    {
        return asset('/public/assets/upload/'.$key);
    }
}
