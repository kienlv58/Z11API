<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    protected $fillable = ['name_text_id','language_id','text_value','describe_value'];
    protected $primaryKey = 'translate_id';
}
