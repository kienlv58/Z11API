<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function groupquestion(){
        return $this->belongsTo('App\GroupQuestion');
    }
    public $primaryKey ='question_id';
    protected $fillable = ['group_question_id','item_code','sub_question_content','sub_question_number','explain_id'];
}
