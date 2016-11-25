<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    protected $fillable = ['explain_id','language_code','text_value','describe_value'];
    protected $primaryKey = 'explain_id';

    public function category(){
        return $this->hasOne('App\Category','explain_id');
    }
    public function folder(){
        return $this->hasOne('App\Folder','explain_id');
    }
    public function package(){
        return $this->hasOne('App\Package','explain_id');
    }
    public function chapter(){
        return $this->hasOne('App\Chapter','explain_id');
    }
    public function groupquestion(){
        return $this->hasManyThrough('App\GroupQuestion','App\Explain','explain_id','explain_id','explain_id');
    }
    public function question(){
        return $this->hasManyThrough('App\Question','App\Explain','explain_id','explain_id','explain_id');
    }
    public function Explain(){
        return $this->hasManyThrough('App\Explain','explain_id');
    }
}