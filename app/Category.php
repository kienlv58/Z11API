<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_code','explain_id'];
    public $primaryKey = 'category_id';
//    public function translate(){
//        return $this->hasOne('App\Translate')
//    }
}
