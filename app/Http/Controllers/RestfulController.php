<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RestfulController extends Controller
{
    public function getProfile($id = 0){
        $user = User::find($id);
        if($user != null) {
            $user->code = 200;
            $user->status = 'OK';
            $user->profile = $user->profile()->get()->first();
            $user = $user->toArray();
            return response()->json($user);
        }else{
            return response()->json(['code'=>404,'status'=>'user not exists']);
        }
    }
}
