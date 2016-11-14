<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Explain extends Model
{
    protected $fillable = ['item_code', 'explain_cost'];
    public $primaryKey = 'explain_id';
    public function category(){
        return $this->hasOne('App\Category');
    }
    public function folder(){
        return $this->hasOne('App\Folder');
    }
    public function chapter(){
        return $this->hasOne('App\Chapter');
    }
    public function groupquestion(){
        return $this->hasOne('App\GroupQuestion');
    }
    public function question(){
        return $this->hasOne('App\Question');
    }
    public function translate(){
        return $this->hasMany('App\Translate','name_text_id','explain_id');
    }
}
