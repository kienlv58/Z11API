<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupQuestion extends Model
{
    public function chapter(){
        return $this->belongsTo('App\Chapter','chapter_id');
    }

    protected $fillable = ['chapter_id','item_code','group_question_audio','group_question_image','group_question_transcript','group_question_content','explain_id'];
    public $primaryKey = 'group_question_id';
    public function question(){
        return $this->hasMany('App\Question','group_question_id');
    }
}
