<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Explain extends Model
{
    protected $fillable = ['explain_id','item_code', 'explain_cost'];
    public $primaryKey = 'explain_id';
    public function group_question(){
        return $this->belongsTo('App\GroupQuestion','explain_id');
}
}
