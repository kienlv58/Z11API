<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_code','explain_id','image'];
    public $primaryKey = 'category_id';
    public function folder(){
        return $this->hasMany('App\Folder','category_id');
    }
    //2-3-1
    public function package(){
        return $this->hasManyThrough('App\Package','App\Folder','category_id','folder_id','package_id');
    }
}
