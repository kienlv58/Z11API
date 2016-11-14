<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public $primaryKey = 'folder_id';
    protected $fillable = ['item_code','category_code','name_text_id','owner_id','type_owner'];
}
