<?php

namespace App\Http\Middleware;

use App\Permission;
use App\Role;
use App\User;
use App\UserRole;
use Closure;
use Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;


class CheckUser
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
        $method = Request::method();

        $path = $request->path();
        $path = 'http~'.$path;

        //role anonymous
        $find_permission = Role::where('name_role','anonymous')->get()->first();
        $arr_role_per = explode('|',$find_permission->role_permission);
        $arr_list_per_user = [];
        foreach ($arr_role_per as $value){
            $per = Permission::where('permission_code',$value)->get()->first();
            array_push($arr_list_per_user,$per);
        }

        foreach ($arr_list_per_user as $value){
            if(strpos($path,$value->path) == true  && ($method == $value->method)){
                return $next($request);
            }
        }
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }



        $user = User::findOrFail($user->id);
        if($user != null){

            //role user
            $find_permission = Role::where('name_role','user')->get()->first();
            $arr_role_per = explode('|',$find_permission->role_permission);
            $arr_list_per_user = [];
            foreach ($arr_role_per as $value){
                $per = Permission::where('permission_code',$value)->get()->first();
                array_push($arr_list_per_user,$per);
            }

            foreach ($arr_list_per_user as $value){
                if(strpos($path,$value->path) == true  && ($method == $value->method)){
                    return $next($request);
                }
            }

            //role other
            $arr_role = UserRole::where('user_id',$user->id)->get();
            $arr_list_per_user2 = [];
            foreach ($arr_role as $value){
                $role = Role::where('name_role',$value->name_role)->get()->first();

                $arr_role_per = explode('|',$role->role_permission);
                foreach ($arr_role_per as $value){
                    if($value == 'all')
                        return $next($request);
                    $per = Permission::where('permission_code',$value)->get()->first();
                    array_push($arr_list_per_user2,$per);
                }

            }
            foreach ($arr_list_per_user2 as $value){
                if(strpos($path,$value->path) == true && ($method == $value->method)){
                    return $next($request);
                }
            }
            return response()->json([400, 'user not permission query'], 400);
        }else{
            return response()->json([400, 'user not exists'], 400);
        }

    }
}
