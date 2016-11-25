<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $primaryKey = 'language_id';
    protected $fillable =['language_id','item_code','language_code','description'];
    public function translate(){
        return $this->hasMany('App\Translate','language_code','language_code');
    }
}
