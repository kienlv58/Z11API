<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uid = $request->input('uid');
        $user = User::find($uid);
        $a = $user->type;
        if ($user == null){
            return response()->json(['code'=>404,'status'=>'user null'],404);
        }else if ($a === 'admin' || $a === 'mod'){
            return $next($request);
        }else{
            return response()->json(['code'=>400,'status'=>'you are not admin or mod'],400);
        }

    }
}
