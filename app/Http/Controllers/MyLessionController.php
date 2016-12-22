<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class MyLessionController extends Controller
{
    public function getMyLession(){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);

    }
   //{"my_lesstion_id","user_id","lesstion":{"package":"1-2-3"}}
    public function addMyLesstion(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);

    }
}
