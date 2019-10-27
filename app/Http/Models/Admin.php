<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'mobile', 'email', 'password'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * create new admin rules list
     * 
     */
    public $create_new = [
        'username' => 'min:6|required',
        'email' => 'requred|unique:admins',
        'password' => 'min:6|required',
        'mobile' => 'reqiored|unique:admins'
    ];

    public function update_rules($id) {
        
        return [
            'username' => 'min:6|required',
            'email' => 'unique:admins,email,'.$id,
            'mobile' => 'unique:admins,mobile,'.$id
        ];   
    }
}
