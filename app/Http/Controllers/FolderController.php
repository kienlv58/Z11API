<?php

namespace App\Http\Controllers;

use App\Category;
use App\Folder;
use App\Language;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class FolderController extends Controller
{
    protected $model ='App\Folder';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\GET(
     *     path="/folder/{id}",
     *     summary="get folder",
     *     tags={"3.Folder"},
     *     description="get folder with folder_id",
     *     operationId="getfolder",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "folder_id",
     *     description = "folder_id",
     *      required = true,
     *      in ="formData",
     *     type = "integer",
     *
     *     @SWG\Schema(
     *     required={"grant_type"},
     *     type = "integer",
     *      )
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
     *         description="get succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid param",
     *     )
     * )
     */
    public  function getFolder($id){
        return $this->getDataById($this->model,$id);

    }

    /**
     * @SWG\GET(
     *     path="/folder_myfolder",
     *     summary="get my folder",
     *     tags={"3.Folder"},
     *     description="get folder with user_id",
     *     operationId="getfolder",
     *     consumes={"application/json"},
     *     produces={"application/json"},
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
     *         description="get succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid param",
     *     )
     * )
     */

    public  function getMyFolder(){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $arr_my_folder = Folder::where('owner_id',$user->id)->get();

        foreach($arr_my_folder as $value){
            $arr_package = $value->package()->get();
            foreach($arr_package as $value){
                $value->translate_name_text = $this->getTranslate($value->name_text_id);
                $value->translate_describe_text = $this->getTranslate($value->describe_text_id);
            }
            $value->translate_name_text = $this->getTranslate($value->name_text_id);
            $value->translate_describe_text = $this->getTranslate($value->describe_text_id);
            $value->packages = $arr_package;
        }
        
        if(count($arr_my_folder) == 0){
            return response()->json($this->setArrayData(400,'you have not folder'),400);
        }else{
            return response()->json($this->setArrayData(200,'success',$arr_my_folder),200);
        }

    }

    /**
     * @SWG\Get(
     *     path="/folders/{limit}/{offset}",
     *     summary="get all folder",
     *     tags={"3.Folder"},
     *     description="return folder with take and skip",
     *     operationId="folder",
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
    public function getAllFolder($limit = 'all',$offset = 0){
        return $this->getAllData($this->model,$limit,$offset);
    }

    /**
     * @SWG\Post(
     *     path="/folders",
     *     summary="add new folder",
     *     tags={"3.Folder"},
     *     description="add new folder",
     *     operationId="folderadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "category_id",
     *      description = "category id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "text_value",
     *     description = "text_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "describe_value",
     *     description = "describe_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"describe_value"},
     *     type = "string",
     *      )
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function addFolder(Request $request){


        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $owner_id = $user->id;

        $data = $request->toArray();
        $check_category = Category::find($data['category_id']);
        if($check_category == null){
            return response()->json($this->setArrayData(400,'category not exists'),400);
        }

        $result = $this->addDataTranslate($data['text_value']);
        $a = \GuzzleHttp\json_decode($result->content(), true);
        $code = $a['code'];
        $name_text_id = $a['metadata']['name_text_id'];
        if ($code === 400)
            return $result;
        $result2 = $this->addDataTranslate($data['describe_value']);
        $b = \GuzzleHttp\json_decode($result2->content(), true);
        $code2 = $b['code'];
        $describe_text_id = $b['metadata']['name_text_id'];
        if ($code2 === 400){
            $this->deleteTextId($name_text_id);
            return $result2;
        }

        $data_folder = ['item_code' => 'folder', 'category_id'=>$data['category_id'],'name_text_id' =>$name_text_id ,'describe_text_id'=>$describe_text_id,'owner_id'=>$owner_id];
        return $this->addNewData($this->model, $data_folder);

    }

    /**
     * @SWG\Put(
     *     path="/folders",
     *     summary="edit a folder",
     *     tags={"3.Folder"},
     *     description="edit folder",
     *     operationId="folderedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "folder_id",
     *      description = "folder id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"folder_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "text_value",
     *     description = "text_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "describe_value",
     *     description = "describe_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"describe_value"},
     *     type = "string",
     *      )
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function editFolder(Request $request){

        $data = $request->toArray();
        $folder = Folder::find($data['folder_id']);
        if ($folder == null) {
            return response()->json($this->setArrayData(400,'can find folder'),400);
        }
        $name_text_id = $folder->name_text_id;
        $describe_text_id = $folder->describe_text_id;
        if(array_key_exists('text_value',$data) == true) {
            $result = $this->EditDataTranslate($data['text_value'], $name_text_id);

            $a = \GuzzleHttp\json_decode($result->content(), true);
            $code = $a['code'];
            if ($code === 400)
                return $result;
        }
        if(array_key_exists('describe_value',$data) == true){
            $result = $this->EditDataTranslate($data['describe_value'], $describe_text_id);
            $a = \GuzzleHttp\json_decode($result->content(), true);
            $code = $a['code'];
            if ($code === 400)
                return $result;
        }
        return response()->json($this->setArrayData(200,'edit success'),200);


    }

    /**
     * @SWG\Delete(
     *     path="/folders/{folder_id}",
     *     summary="delete folder ",
     *     tags={"3.Folder"},
     *     description="delete with folder_id",
     *     operationId="folderdelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "folder_id",
     *      description = "folder_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_id"},
     *     type = "integer"
     *      )
     *           ),
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
    public function deleteFolder($folder_id){
        $folder = Folder::where('folder_id',$folder_id)->get()->first();
        if ($folder == null) {
            return response()->json($this->setArrayData(400, 'can not find to folder id'), 400);
        }
        $name_text_id = $folder->name_text_id;
        $describe_text_id = $folder->describe_text_id;
        $this->deleteTextId($describe_text_id);
        return $this->deleteTextId($name_text_id);
    }





}


