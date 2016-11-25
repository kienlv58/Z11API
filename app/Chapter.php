<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public function folder(){
        return $this->belongsTo('App\Package','package_id');
    }
    public $primaryKey = 'chapter_id';
    protected $fillable = ['item_code','package_id','explain_id'];
}
