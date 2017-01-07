<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public $model = 'App\UserRole';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Get(
     *     path="/admin/user_role/{user_id}",
     *     summary="get user_roles with user_id",
     *     tags={"UserRole"},
     *     description="return roles with name_role",
     *     operationId="role",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *     in ="path",
     *     description = "user_id",
     *     type = "string",
     *    required = true
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

    public function getUserRole($user_id){
        $user_role = UserRole::where('user_id',$user_id)->get()->first();
        if($user_role == null){
            return response()->json($this->setArrayData(400, 'role default is user'), 400);
        }
        return response()->json($this->setArrayData(200, 'success',$user_role), 200);
    }
    /**
     * @SWG\Get(
     *     path="/admin/user_roles/{limit}/{offset}",
     *     summary="get all user_roles",
     *     tags={"UserRole"},
     *     description="return user_roles with take and skip",
     *     operationId="role",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "limit",
     *     in ="path",
     *     description = "take from ....",
     *     type = "integer",
     *     default = "all",
     *    required = true
     *     ),
     *      @SWG\Parameter(
     *      name = "offset",
     *     in ="path",
     *     description = "skip from",
     *     type = "integer",
     *     default="0",
     *     required = true
     *     ),
     *
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

    public function getAllUserRoles($limit = 'all',$offset = 0){
        return $this->getAllData($this->model,$limit,$offset);
    }

    /**
     * @SWG\Post(
     *     path="/admin/user_roles",
     *     summary="add new user_roles",
     *     tags={"UserRole"},
     *     description="add new user_roles",
     *     operationId="add_user_roles",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "name_role",
     *      description = "name_role",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name_role"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "user_id",
     *      description = "user_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"user_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "expired",
     *      description = "expired of role, forever  = -1",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"expired"},
     *     type = "integer"
     *      )
     *           ),
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */

    public function addUserRole(Request $request){
        $data = $request->toArray();
        $user = User::find($data['user_id']);
        if($user == null){
            return response()->json($this->setArrayData(400, "user not exist"), 400);
        }

        $find_role = Role::where('name_role',$data['name_role'])->get()->first();
        if($find_role ==  null){
            return response()->json($this->setArrayData(400, 'role not exist'), 400);
        }
        $find_user_role = UserRole::where('user_id',$data['user_id'])->where('name_role',$data['name_role'])->get()->first();
        if($find_user_role != null){
            return response()->json($this->setArrayData(400, "user role exist"), 400);
        }
        $expired = $data['expired'];
        $data['deadline'] = date('Y-m-d h:i',strtotime("+$expired days"));
        return $this->addNewData($this->model,$data);

    }

    /**
     * @SWG\Put(
     *     path="/admin/user_roles",
     *     summary="add new user_roles",
     *     tags={"UserRole"},
     *     description="add new user_roles",
     *     operationId="aa_user_roles",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "name_role",
     *      description = "name_role",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name_role"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "user_id",
     *      description = "user_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"user_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "expired",
     *      description = "expired of role, forever = -1",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"expired"},
     *     type = "string"
     *      )
     *           ),
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */

    public function editUserRole(Request $request){
        $data = $request->toArray();
        $user = UserRole::where('user_id',$data['user_id'])->where('name_role',$data['name_role'])->get()->first();
        if($user == null){
            return response()->json($this->setArrayData(400, "user role not exist"), 400);
        }

        $find_role = Role::where('name_role',$data['name_role'])->get()->first();
        if($find_role ==  null){
            return response()->json($this->setArrayData(400, 'role not exist'), 400);
        }
        $expired = $data['expired'];
        $result = $user->update(['expired'=>$data['expired'],'deadline'=>date('Y-m-d h:i',strtotime("+$expired days"))]);
        if($result){
            return response()->json($this->setArrayData(200, 'edit success'), 200);
        }else{
            return response()->json($this->setArrayData(400, 'edit error'), 400);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/admin/user_roles/{user_id}/{name_role}",
     *     summary="delete roles ",
     *     tags={"UserRole"},
     *     description="delete with name_role",
     *     operationId="name_role",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *      description = "user_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"user_id"},
     *     type = "integer"
     *      )
     *      ),
     *    @SWG\Parameter(
     *      name = "name_role",
     *      description = "name_role",
     *     in ="path",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name_role"},
     *     type = "string"
     *      )
     *      ),
     *
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
     *         description="delete succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function deleteUserRole($user_id,$name_role){
        $find_user_role = UserRole::where('name_role',$name_role)->where('user_id',$user_id)->get()->first();
        if($find_user_role ==  null){
            return response()->json($this->setArrayData(400, 'role not exist'), 400);
        }
        $result = $find_user_role->delete();
        if($result){
            return response()->json($this->setArrayData(200, 'delete success'), 200);
        }else{
            return response()->json($this->setArrayData(400, 'delete error'), 400);
        }

    }
}
