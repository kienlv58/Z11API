<?php

namespace App\Http\Controllers;

use App\Package;
use App\Profile;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function getUser($id){
        return $this->getDataById('App\User',$id);
    }


    public function getuser_Mod(){
        $mod = User::where('type','mod')->get();
        if($mod != null)
        return response()->json($this->setArrayData(200,'success',$mod->toArray()),200);
        else
            return response()->json($this->setArrayData(400,'null'),400);
    }



    public function getAllUser($take = 'all',$skip = 0){
        return $this->getAllData('App\User',$take,$skip);
    }

    /**
     * @SWG\Post(
     *     path="/admin/delete_user",
     *     summary="delete user ",
     *     tags={"9.Admin"},
     *     description="delete with user_id",
     *     operationId="user_delete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "current_uid",
     *      description = "current_uid",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid delete",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_code"},
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

    public function deleteUser(Request $request){
        $data = $request->toArray();
        $user = User::find($data['current_uid']);
        if($user == null or $user->type != 'admin') {
            return response()->json($this->setArrayData(400, 'you not permission delete to user or user = null'));
        }
        return $this->deleteDataById('App\User',['id'=>$data['uid']]);
    }

    /**
     * @SWG\Post(
     *     path="/admin/create_user_mod",
     *     summary="create_user_mod ",
     *     tags={"9.Admin"},
     *     description="create_user_mod",
     *     operationId="create_user_mod",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "current_uid",
     *      description = "current_uid",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "email",
     *      description = "email",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
     *  @SWG\Parameter(
     *      name = "password",
     *      description = "password",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"password"},
     *     type = "string"
     *      )
     *           ),
     *   @SWG\Parameter(
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
     *   @SWG\Parameter(
     *      name = "gender",
     *      description = "gender",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"gender"},
     *     type = "string"
     *      )
     *           ),
     *   @SWG\Parameter(
     *      name = "coin",
     *      description = "coin",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"coin"},
     *     type = "string"
     *      )
     *           ),
     *     *  @SWG\Parameter(
     *      name = "image",
     *      description = "image",
     *     in ="formData",
     *     required = true,
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

    //'email','password',name','gender','coin','image'
    public function createUserMod(Request $request){
        $data = $request->toArray();
        $user = User::find($data['current_uid']);
        if($user == null or $user->type != 'admin') {
            return response()->json($this->setArrayData(400, 'you not permission delete to user or user = null'));
        }

        $user = User::create(['grant_type'=>'password','email'=>$data['email'],'type'=>'mod','password'=>bcrypt($data['password']),'active'=>0]);
        if($user == null){
            return response()->json($this->setArrayData(400, 'create user mod fail'));
        }
        $uid = $user->id;
        $profile = Profile::create(['user_id'=>$uid,'image'=>$data['image'],'name'=>$data['name'],'gender'=>$data['gender'],'coin'=>$data['coin']]);
        if($profile == null){
            return response()->json($this->setArrayData(400, 'create user mod fail'));
            $this->deleteDataById('App\User',['id'=>$uid]);
        }
        return response()->json($this->setArrayData(400, 'create mod user success'));

    }

    /**
     * @SWG\Post(
     *     path="/admin/aprroval_package",
     *     summary="aprroval user ",
     *     tags={"9.Admin"},
     *     description="aprroval package",
     *     operationId="aprroval package",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *      description = "package_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"package_id"},
     *     type = "integer"
     *      )
     *           ),
     *     *     @SWG\Parameter(
     *      name = "aprroval",
     *      description = "aprroval",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"aprroval"},
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
    public function aprrovalPackage(Request $request){
        $data = $request->toArray();
       // dd($data);
        $package = Package::find($data['package_id']);
        if($package == null){
            return response()->json($this->setArrayData(400, 'can not find to package'), 400);
        }
        if($package->aprrovala == 1){
            return response()->json($this->setArrayData(400, 'package is aprroved'), 400);
        }
        if($data['aprroval'] === '1' || $data['aprroval'] === '2'){
            return $this->editData('App\Package',['approval'=>$data['aprroval']],['package_id'=>$data['package_id']]);
        }
        return response()->json($this->setArrayData(400, 'param aprroval not exist'), 400);

    }
}