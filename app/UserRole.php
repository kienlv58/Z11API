<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $fillable = ['user_role_id','user_id','name_role','date_start','expired'];
    public $primaryKey = 'user_role_id';
}