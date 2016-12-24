<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{

    public $primaryKey = 'package_id';
    protected $fillable = ['package_id','folder_id','item_code','owner_id','type_owner','name_text_id','describe_text_id','package_cost','approval','balance_rate','count_user_rate'];
    public function chapter(){
        return $this->hasMany('App\Chapter','package_id');
    }
}
