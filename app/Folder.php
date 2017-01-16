<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function package(){
        return $this->hasMany('App\Package','folder_id');
    }
    public $primaryKey = 'folder_id';
    protected $fillable = ['item_code','category_id','name_text_id','describe_text_id','owner_id'];
}
