<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RestfulController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/restricted/profile/{id}",
     *     summary="get user from id",
     *     tags={"1.User"},
     *     description="return user from id",
     *     operationId="user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "id",
     *     in ="path",
     *     description = "user_id",
     *     required = true,
     *     type = "integer"
     *     ),
     *     @SWG\Parameter(
     *      name = "Authorization",
     *     in ="header",
     *     description = "token",
     *     required = true,
     *     default = "Bearer {your_token}",
     *     type = "string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid value",
     *     )
     * )
     */
    public function getProfile($id = 0){
        $user = User::find($id);
        if($user != null) {
            $profile = $user->profile()->get()->first();
            return response()->json(['code'=>200,'status'=>'OK','metadata'=>['user'=>$user,'profile'=>$profile]]);
        }else{
            return response()->json(['code'=>404,'status'=>'user not exists'],404);
        }
    }

}
