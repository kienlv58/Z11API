<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public function folder(){
        return $this->belongsTo('App\Folder');
    }
    public $primaryKey = 'package_id';
}
