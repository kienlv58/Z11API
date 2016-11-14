<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id','iamge','gender'];
    protected $hidden = ['id','user_id'];
    public function user() {
        return $this->belongsTo('App\User');
    }
}