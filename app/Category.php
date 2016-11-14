<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
//    protected $fillable = ['category_id','category_code','name_text_id','describe_text_id','created_at','updated_at'];
    protected $fillable = ['category_code','name_text_id'];
    public $primaryKey = 'category_id';
    public function explain(){
        return $this->hasOne('App\Explain','explain_id');
    }
    public function translate(){
        return $this->hasManyThrough('App\Translate','App\Explain','explain_id','name_text_id','category_id');
    }

}
