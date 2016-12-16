<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public $model = 'App\Role';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\Get(
     *     path="/admin/roles/{name_role}",
     *     summary="get roles with name_role",
     *     tags={"Role"},
     *     description="return roles with name_role",
     *     operationId="role",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "name_role",
     *     in ="path",
     *     description = "name_role",
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

    public function getRole($name_role){
        $role = Role::where('name_role',$name_role)->get()->first();
        if($role == null){
            return response()->json($this->setArrayData(400, 'role not exist'), 400);
        }
        return response()->json($this->setArrayData(200, 'success',$role), 200);
    }
    /**
     * @SWG\Get(
     *     path="/admin/roles/{limit}/{offset}",
     *     summary="get all roles",
     *     tags={"Role"},
     *     description="return roles with take and skip",
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

    public function getAllRoles($limit = 'all',$offset = 0){
        return $this->getAllData($this->model,$limit,$offset);
    }

    /**
     * @SWG\Post(
     *     path="/admin/roles",
     *     summary="add new roles",
     *     tags={"Role"},
     *     description="add new roles",
     *     operationId="addroles",
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
     *      name = "role_permission",
     *      description = "role_permission",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"role_permission"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "role_description",
     *      description = "role_description",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"role_description"},
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

    public function addRole(Request $request){
        $data = $request->toArray();
        $find_role = Role::where('name_role',$data['name_role'])->get()->first();
        if($find_role !=  null){
            return response()->json($this->setArrayData(400, 'role exist'), 400);
        }
        $arr_role_permission = explode('|',$data['role_permission']);
        foreach ($arr_role_permission as $value){
            $check_role_permission = Permission::where('permission_code',$value)->get()->first();
            if($check_role_permission == null){
                return response()->json($this->setArrayData(400, "permission $value not exist"), 400);
            }
        }
        return $this->addNewData($this->model,$data);

    }

    /**
     * @SWG\Put(
     *     path="/admin/roles",
     *     summary="add new roles",
     *     tags={"Role"},
     *     description="add new roles",
     *     operationId="addroles",
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
     *      name = "role_permission",
     *      description = "role_permission",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"role_permission"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "role_description",
     *      description = "role_description",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"role_description"},
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

    public function editRole(Request $request){
        $data = $request->toArray();
        $find_role = Role::where('name_role',$data['name_role'])->get()->first();
        if($find_role ==  null){
            return response()->json($this->setArrayData(400, 'role not exist'), 400);
        }
        $arr_role_permission = explode('|',$data['role_permission']);
        foreach ($arr_role_permission as $value){
            $check_role_permission = Permission::where('permission_code',$value)->get()->first();
            if($check_role_permission == null){
                return response()->json($this->setArrayData(400, "permission $value not exist"), 400);
            }
        }
        return $this->editData($this->model,$data,['name_role'=>$data['name_role']]);
    }

    /**
     * @SWG\Delete(
     *     path="/admin/roles/{name_role}",
     *     summary="delete roles ",
     *     tags={"Role"},
     *     description="delete with name_role",
     *     operationId="name_role",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
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
    public function deleteRole($name_role){
        $find_role = Role::where('name_role',$name_role)->get()->first();
        if($find_role ==  null){
            return response()->json($this->setArrayData(400, 'role not exist'), 400);
        }
        $result = $find_role->delete();
        if($result){
            return response()->json($this->setArrayData(200, 'delete success'), 200);
        }else{
            return response()->json($this->setArrayData(400, 'delete error'), 400);
        }

    }
}
