<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question_id', 'item_code', 'answer_item_value', 'answer_is_correct'];
    public $primaryKey = 'answer_item_id';
    protected $table = 'answer_item';
}
