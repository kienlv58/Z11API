<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'email', 'password','type','grant_type','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public $primaryKey = 'id';
    public $timestamps = true;

    public function profile() {
        return $this->hasOne('App\Profile','user_id','id');
    }
    public function folder(){
        return $this->hasOne('App\Folder');
    }
}
