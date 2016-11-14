<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupQuestion extends Model
{
    public function chapter(){
        return $this->belongsTo('App\Chapter','chapter_id');
    }
    //public
}
