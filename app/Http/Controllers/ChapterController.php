<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Language;
use App\Package;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    protected $model ='App\Chapter';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\GET(
     *     path="/chapters/{id}",
     *     summary="get chapter",
     *     tags={"5.Chapter"},
     *     description="get chapter with chapter_id",
     *     operationId="getchapter",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "chapter_id",
     *     description = "chapter_",
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
    public  function getChapter($id){
        return $this->getDataById($this->model,$id);

    }
    /**
     * @SWG\Get(
     *     path="/chapters/{limit}/{offset}",
     *     summary="get all Chapter",
     *     tags={"5.Chapter"},
     *     description="return chapter with take and skip",
     *     operationId="Chapter",
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
    public function getAllChapter($limit = 'all',$offset = 0){
        return $this->getAllData($this->model,$limit,$offset);
    }

    /**
     * @SWG\Post(
     *     path="/chapters",
     *     summary="add new chapter",
     *     tags={"5.Chapter"},
     *     description="add new chapter",
     *     operationId="packageadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *      description = "package_id ",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"package_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "name_text",
     *     description = "name_text",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"name_text"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "describe_text",
     *     description = "describe_text ",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"describe_text"},
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
    public function addChapter(Request $request){

        $data = $request->toArray();
        $check_package = Package::find($data['package_id']);
        if($check_package == null){
            return response()->json($this->setArrayData(400,'package not exists'),400);
        }
        $data_chapter= ['item_code' => 'chapter', 'package_id'=>$data['package_id'],'name_text' =>$data['name_text'] ,'describe_text'=>$data['describe_text']];
        return $this->addNewData($this->model, $data_chapter);
    }

    /**
     * @SWG\Put(
     *     path="/chapters",
     *     summary="edit a Chapter",
     *     tags={"5.Chapter"},
     *     description="edit Chapter",
     *     operationId="Chapteredit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "chapter_id",
     *      description = "chapter_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"chapter_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "name_text",
     *     description = "name_text",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"name_text"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "describe_text",
     *     description = "describe_text ",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"describe_text"},
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
    public function editChapter(Request $request){
        $data = $request->toArray();
        $chapter = Chapter::find($data['chapter_id']);
        if ($chapter == null) {
            return response()->json($this->setArrayData(400,'can find folder'),400);
        }
        $datachapter = ['name_text'=>$data['name_text'],'describe_text'=>$data['describe_text']];
        return $this->editData($this->model, $datachapter,['chapter_id'=>$data['chapter_id']]);


    }

    /**
     * @SWG\Delete(
     *     path="/chapter/delete",
     *     summary="delete chapter ",
     *     tags={"5.Chapter"},
     *     description="delete with chapter_id",
     *     operationId="chapterdelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "chapter_id",
     *      description = "chapter_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"chapter_id"},
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
    public function deleteChapter($chapter_id){
        $chapter = Chapter::where('chapter_id',$chapter_id)->get()->first();
        if ($chapter == null) {
            return response()->json($this->setArrayData(400, 'can not find to chapter id'), 400);
        }
        return $this->deleteDataById($this->model,['chapter_id'=>$chapter_id]);
    }
}
