<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public function folder(){
        return $this->belongsTo('App\Folder','folder_id');
    }
    public $primaryKey = 'package_id';
    protected $fillable = ['package_id','folder_id','item_code','owner_id','type_owner','explain_id','package_cost'];
}
