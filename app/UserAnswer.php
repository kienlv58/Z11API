<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    public $primaryKey = 'user_answer_id';
    protected $fillable = ['user_id','item_id','item_code','status','answer_result','answer_time'];
    public function Question(){
        return $this->belongsTo('App\Question','item_id','question_id');
    }
}
