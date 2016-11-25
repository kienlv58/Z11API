<?php

namespace App\Http\Controllers;

use App\Explain;
use App\GroupQuestion;
use App\Language;
use App\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected $model ='App\Question';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\GET(
     *     path="/question/get/{id}",
     *     summary="get question",
     *     tags={"7.Question"},
     *     description="get group_question with question_id",
     *     operationId="get question",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "question_id",
     *     description = "question_id",
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
    public  function getQuestion($id){
        return $this->getDataById($this->model,$id);

    }
    /**
     * @SWG\Get(
     *     path="/question/get_all/{take}/{skip}",
     *     summary="get all question",
     *     tags={"7.Question"},
     *     description="return question with take and skip",
     *     operationId="question",
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
    public function getAllQuestion($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }

    /**
     * @SWG\Post(
     *     path="/question/add",
     *     summary="add new question",
     *     tags={"7.Question"},
     *     description="add new question",
     *     operationId="question_add",
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
     *      name = "sub_question_content",
     *      description = "sub_question_content",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"sub_question_content"},
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
    public function addQuestion(Request $request){
        $data_qestion = $request->only(['group_question_id','sub_question_content']);

        $check_group_qs = GroupQuestion::find($data_qestion['group_question_id']);
        if($check_group_qs == null){
            return response()->json($this->setArrayData(400,'group question not exists'),400);
        }
        $explain_id = $this->addNewDataExplain('question',$request->input('explain_cost'));
        $result = $this->addDataTranslate($request->input('translate'),$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;

        $data_qestion['explain_id'] = $explain_id;
        $data_qestion['item_code'] = 'question';

        return $this->addNewData($this->model,$data_qestion);
    }

    /**
     * @SWG\Post(
     *     path="/question/edit",
     *     summary="edit group_question",
     *     tags={"7.Question"},
     *     description="edit question",
     *     operationId="question_edit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "question_id",
     *      description = "question_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"question_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "sub_question_content",
     *      description = "sub_question_content",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"sub_question_content"},
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
    public function editQuestion(Request $request){

        $data = $request->toArray();
        $question = Question::find($data['question_id']);
        if ($question == null) {
            return response()->json($this->setArrayData(400,'can find group question'),400);
        }
        $explain_id = $question->explain_id;
        Explain::where('explain_id',$explain_id)->update(['explain_cost'=>$data['explain_cost']]);
        $this->deleteDataTranslate($explain_id);
        $result = $this->addDataTranslate($data['translate'],$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        return $this->editData($this->model,$request->only(['sub_question_content']),['question_id'=>$data['question_id']]);


    }

    /**
     * @SWG\Post(
     *     path="/question/delete",
     *     summary="delete group_question ",
     *     tags={"7.Question"},
     *     description="delete with question",
     *     operationId="questiondelete",
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
     *      name = "question_id",
     *      description = "question_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"question_id"},
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
    public function deleteQuestion(Request $request){
        $data = $request->toArray();
        $question = Question::find($data['question_id']);
        if ($question == null) {
            return response()->json($this->setArrayData(400, 'can not find to question'), 400);
        }
        $explain_id = $question->explain_id;
        return $this->deleteDataExplain($explain_id);
    }
}
