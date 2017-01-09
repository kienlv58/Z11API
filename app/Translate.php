<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    protected $fillable = ['text_id','language_code','text_value', 'translate_id'];
    public $primaryKey ='translate_id';

    public function category(){
        return $this->hasOne('App\Category','name_text_id');
    }
    public function folder(){
        return $this->hasOne('App\Folder','name_text_id');
    }
    public function package(){
        return $this->hasOne('App\Package','name_text_id');
    }
    public function chapter(){
        return $this->hasOne('App\Chapter','name_text_id');
    }
    public function groupquestion(){
        return $this->hasManyThrough('App\GroupQuestion','App\Explain','name_text_id','text_id','name_text_id');
    }
    public function question(){
        return $this->hasManyThrough('App\Question','App\Explain','name_text_id','text_id','name_text_id');
    }
    public function Explain(){
        return $this->hasManyThrough('App\Explain','name_text_id');
    }
}