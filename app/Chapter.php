<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public function folder(){
        return $this->belongsTo('App\Package','package_id');
    }
    public $primaryKey = 'chapter_id';
    protected $fillable = ['item_code','package_id','name_text','describe_text'];
    public function groupquestion(){
        return $this->hasMany('App\GroupQuestion','chapter_id');
    }
    public function package(){
        return $this->belongsTo('App\Package','package_id');
    }
}
