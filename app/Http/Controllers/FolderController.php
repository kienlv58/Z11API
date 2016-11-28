<?php

namespace App\Http\Controllers;

use App\Category;
use App\Folder;
use App\Language;
use App\User;
use Illuminate\Http\Request;

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
     *     path="/folder/get/{id}",
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
     * @SWG\Get(
     *     path="/folder/get_all/{take}/{skip}",
     *     summary="get all folder",
     *     tags={"3.Folder"},
     *     description="return folder with take and skip",
     *     operationId="folder",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "take",
     *     in ="path",
     *     description = "take from ....",
     *     type = "integer",
     *     default = "all",
     *    required = true
     *     ),
     *      @SWG\Parameter(
     *      name = "skip",
     *     in ="path",
     *     description = "skip from",
     *     type = "integer",
     *     default="0",
     *     required = true
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
    public function getAllFolder($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }

    /**
     * @SWG\Post(
     *     path="/folder/add",
     *     summary="add new folder",
     *     tags={"3.Folder"},
     *     description="add new folder",
     *     operationId="folderadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "owner_id",
     *      description = "uid create",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "type_owner",
     *      description = "type_owner select : admin/mod/user",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
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
     *      name = "translate",
     *      description = "translate json",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
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
        $data_folder = $request->only(['category_id','owner_id','type_owner']);

        $check_category = Category::find($data_folder['category_id']);
        if($check_category == null){
            return response()->json($this->setArrayData(400,'category not exists'),400);
        }
        $check_user = User::find($data_folder['owner_id']);
        if($check_user == null){
            return response()->json($this->setArrayData(400,'owner_id: user not exists'),400);
        }


        $explain_id = $this->addNewDataExplain('folder',0);
        $result = $this->addDataTranslate($request->input('translate'),$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        $data_folder['item_code']='folder';
        $data_folder['explain_id']=$explain_id;

        return $this->addNewData($this->model,$data_folder);
    }

    /**
     * @SWG\Post(
     *     path="/folder/edit",
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
     *      name = "translate",
     *      description = "translate json",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
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
        $explain_id = $folder->explain_id;
        $this->deleteDataTranslate($explain_id);
        $result = $this->addDataTranslate($data['translate'],$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        else
            return response()->json($this->setArrayData(200,'edit success'),200);


    }

    /**
     * @SWG\Post(
     *     path="/folder/delete",
     *     summary="delete folder ",
     *     tags={"3.Folder"},
     *     description="delete with folder_id",
     *     operationId="folderdelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
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
     *     @SWG\Parameter(
     *      name = "folder_id",
     *      description = "folder_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_id"},
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
    public function deleteFolder(Request $request){
        $data = $request->toArray();
        $folder = Folder::find($data['folder_id']);
        if ($folder == null) {
            return response()->json($this->setArrayData(400, 'can not find to folder'), 400);
        }
        $explain_id = $folder->explain_id;
        return $this->deleteDataExplain($explain_id);
    }





}


