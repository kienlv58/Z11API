<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $fillable = ['permission_id','permission_code','description'];
    public $primaryKey = 'permission_id';
}
