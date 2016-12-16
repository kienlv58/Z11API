<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class SessionRole extends Model
{
    public $fillable = ['session_role_id', 'token', 'user_id', 'expired'];
    public $primaryKey = 'session_role_id';



}
