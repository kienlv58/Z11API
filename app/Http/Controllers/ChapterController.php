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
     *     path="/chapter/get/{id}",
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
     *     path="/chapter/get_all/{take}/{skip}",
     *     summary="get all Chapter",
     *     tags={"5.Chapter"},
     *     description="return chapter with take and skip",
     *     operationId="Chapter",
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
    public function getAllChapter($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }

    /**
     * @SWG\Post(
     *     path="/chapter/add",
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
    public function addChapter(Request $request){
        $data_chapter = $request->only(['package_id']);

        $check_package = Package::find($data_chapter['package_id']);
        if($check_package == null){
            return response()->json($this->setArrayData(400,'package not exists'),400);
        }
        $explain_id = $this->addNewDataExplain('chapter',0);
        $result = $this->addDataTranslate($request->input('translate'),$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;

        $data_chapter['explain_id'] = $explain_id;
        $data_chapter['item_code'] = 'chapter';

        return $this->addNewData($this->model,$data_chapter);
    }

    /**
     * @SWG\Post(
     *     path="/chapter/edit",
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
     *
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
    public function editChapter(Request $request){

        $data = $request->toArray();
        $chapter = Chapter::find($data['chapter_id']);
        if ($chapter == null) {
            return response()->json($this->setArrayData(400,'can find folder'),400);
        }
        $explain_id = $chapter->explain_id;
        $this->deleteDataTranslate($explain_id);
        $result = $this->addDataTranslate($data['translate'],$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        return response()->json($this->setArrayData(200, 'edit successfull'), 200);


    }

    /**
     * @SWG\Post(
     *     path="/chapter/delete",
     *     summary="delete chapter ",
     *     tags={"5.Chapter"},
     *     description="delete with chapter_id",
     *     operationId="chapterdelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid delete",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"uid"},
     *     type = "integer"
     *      )
     *           ),
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
    public function deleteChapter(Request $request){
        $data = $request->toArray();
        $folder = Chapter::find($data['chapter_id']);
        if ($folder == null) {
            return response()->json($this->setArrayData(400, 'can not find to chapter'), 400);
        }
        $explain_id = $folder->explain_id;
        return $this->deleteDataExplain($explain_id);
    }
}
