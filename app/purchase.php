<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    protected $fillable = ['purchase_id', 'user_id','item_id','item_code'];
    public $primaryKey = 'purchase_id';
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
