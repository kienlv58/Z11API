<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Explain;
use App\GroupQuestion;
use App\Language;
use Illuminate\Http\Request;

class GroupQuestionController extends Controller
{
    protected $model ='App\GroupQuestion';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\GET(
     *     path="/group_question/get/{id}",
     *     summary="get group_question",
     *     tags={"6.GroupQuestion"},
     *     description="get group_question with group_question_id",
     *     operationId="get group_question",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "group_question_id",
     *     description = "group_question_id",
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
    public  function getGroupQuestion($id){
        return $this->getDataById($this->model,$id);

    }
    /**
     * @SWG\Get(
     *     path="/group_question/get_all/{take}/{skip}",
     *     summary="get all group_question",
     *     tags={"6.GroupQuestion"},
     *     description="return group_question with take and skip",
     *     operationId="group_question",
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
    public function getAllGroupQuestion($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }

    /**
     * @SWG\Post(
     *     path="/group_question/add",
     *     summary="add new group_question",
     *     tags={"6.GroupQuestion"},
     *     description="add new group_question",
     *     operationId="group_question_add",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "chapter_id",
     *      description = "chapter_id ",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"chapter_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "group_question_content",
     *      description = "group_question_content",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"group_question_content"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "group_question_transcript",
     *      description = "group_question_transcript",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"	group_question_transcript"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "group_question_image",
     *      description = "group_question_image",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"group_question_image"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "group_question_audio",
     *      description = "group_question_audio",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"group_question_audio"},
     *     type = "string"
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
     *     @SWG\Parameter(
     *      name = "explain_cost",
     *     description = "explain_cost",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"explain_cost"},
     *     type = "string",
     *      )
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
    public function addGroupQuestion(Request $request){
        $data_group_qs = $request->only(['chapter_id','group_question_content','group_question_transcript','group_question_image','group_question_audio']);

        $check_chapter = Chapter::find($data_group_qs['chapter_id']);
        if($check_chapter == null){
            return response()->json($this->setArrayData(400,'chapter not exists'),400);
        }
        $explain_id = $this->addNewDataExplain('group_qs',$request->input('explain_cost'));
        $result = $this->addDataTranslate($request->input('translate'),$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;

        $data_group_qs['explain_id'] = $explain_id;
        $data_group_qs['item_code'] = 'group_qs';

        return $this->addNewData($this->model,$data_group_qs);
    }

    /**
     * @SWG\Post(
     *     path="/group_question/edit",
     *     summary="edit group_question",
     *     tags={"6.GroupQuestion"},
     *     description="edit group_question",
     *     operationId="group_question_edit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "group_question_id",
     *      description = "group_question_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"group_question_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "group_question_content",
     *      description = "group_question_content",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"group_question_content"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "group_question_transcript",
     *      description = "group_question_transcript",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"	group_question_transcript"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "group_question_image",
     *      description = "group_question_image",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"group_question_image"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "group_question_audio",
     *      description = "group_question_audio",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"group_question_audio"},
     *     type = "string"
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
     *      @SWG\Parameter(
     *      name = "explain_cost",
     *     description = "explain_cost",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"explain_cost"},
     *     type = "string",
     *      )
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
    public function editGroupQuestion(Request $request){

        $data = $request->toArray();
        $group_qs = GroupQuestion::find($data['group_question_id']);
        if ($group_qs == null) {
            return response()->json($this->setArrayData(400,'can find group question'),400);
        }
        $explain_id = $group_qs->explain_id;
        Explain::where('explain_id',$explain_id)->update(['explain_cost'=>$data['explain_cost']]);
        $this->deleteDataTranslate($explain_id);
        $result = $this->addDataTranslate($data['translate'],$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        return $this->editData($this->model,$request->only(['group_question_content','group_question_transcript','group_question_image','group_question_audio']),['group_question_id'=>$data['group_question_id']]);


    }

    /**
     * @SWG\Post(
     *     path="/group_question/delete",
     *     summary="delete group_question ",
     *     tags={"6.GroupQuestion"},
     *     description="delete with group_question",
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
     *      name = "group_question_id",
     *      description = "group_question_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"group_question_id"},
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
    public function deleteGroupQuestion(Request $request){
        $data = $request->toArray();
        $group_qs = GroupQuestion::find($data['group_question_id']);
        if ($group_qs == null) {
            return response()->json($this->setArrayData(400, 'can not find to group question'), 400);
        }
        $explain_id = $group_qs->explain_id;
        return $this->deleteDataExplain($explain_id);
    }
}
