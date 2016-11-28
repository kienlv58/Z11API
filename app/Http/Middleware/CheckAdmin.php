<?php

namespace App\Http\Middleware;

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
       if(Auth::check() or $request->user()->type != 'admin' or $request->user()->type != 'mod' ){
           return response()->json(['code'=>400,'status'=>'you are not admin'],400);
       }
        return $next($request);
    }
}
