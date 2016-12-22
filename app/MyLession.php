<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyLession extends Model
{
    public $fillable = ['my_lession_id','user_id','lession'];
    public $primaryKey = 'my_lession_id';
}
