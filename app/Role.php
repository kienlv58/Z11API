<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $fillable =['role_id','name_role','role_permission','role_description'];
    public $primaryKey ='role_id';
}
