<?php

namespace App\Http\Controllers;

use App\Profile;
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

    /**
     * @SWG\Post(
     *     path="/restricted/profile/edit",
     *     summary="edit profile ",
     *     tags={"1.User"},
     *     description="edit with profile_id",
     *     operationId="profileedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *      description = "user_id edit",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"user_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "name",
     *      description = "name",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "image",
     *      description = "image",
     *     in ="formData",
     *     type="string",
     *     @SWG\Schema(
     *     required={"image"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Response(
     *         response=200,
     *         description="delete succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function editProfile(Request $request){
        $data = $request->toArray();
        $user = User::find($data ['user_id']);
        if($user == null)
            return response()->json(['code'=>404,'status'=>'user not exists'],404);
        return $this->editData('App\Profile',$data,['user_id'=>$data['user_id']]);
        //$result = Profile::where('user_id',$request['user_id'])->update(['name'=>$request['name'],'image'=>$request['image']]);

    }
    /**
     * @SWG\Post(
     *     path="/restricted/chargecoin",
     *     summary="chargecoin",
     *     tags={"1.User"},
     *     description="chargecoin",
     *     operationId="chargecoin",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *      description = "uid chargecoin",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"uid"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "coin",
     *      description = "coin charge",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"name"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Response(
     *         response=200,
     *         description="delete succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function chargeCoin(Request $request){
        $data = $request->toArray();
        $profile = Profile::where('user_id',$data['user_id'])->get()->first();
        if($profile == null)
            return response()->json(['code'=>404,'status'=>'user not exists'],404);
        else{
            $old_coin = $profile->coin;
            $new_coin = $old_coin + $data['coin'];
            $profile->update(['coin'=>$new_coin]);
            return response()->json(['code'=>200,'status'=>'charge success','coin current'=>$new_coin],200);
        }
    }


}
