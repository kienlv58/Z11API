<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActions extends Model
{
    public $fillable =  ['action_id','rate_item','user_id','mylession_item'];
    public $primaryKey = 'action_id';
}
