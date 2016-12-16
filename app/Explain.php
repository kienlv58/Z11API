<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Explain extends Model
{
    protected $fillable = ['explain_item_id','item_code', 'explain_cost','explain_text_id'];
    public $primaryKey = 'explain_item_id';
    public function explain(){
        return $this->belongsTo('App\Explain','explain_text_id','text_id');
}
}
