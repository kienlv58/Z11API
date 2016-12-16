<?php

namespace App\Http\Controllers;

use App\Explain;
use App\GroupQuestion;
use App\Language;
use App\Question;
use App\TextId;
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
    public  function getQuestion($question_id){
        return $this->getDataById($this->model,$question_id);

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
    public function getAllQuestion($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }

    /**
     * @SWG\Post(
     *     path="/questions",
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
     *
     *     @SWG\Parameter(
     *      name = "explain",
     *     description = "explain josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"explain"},
     *     type = "string",
     *      )
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


        $data = $request->toArray();
        $check_group_qs = GroupQuestion::find($data['group_question_id']);
        if($check_group_qs == null){
            return response()->json($this->setArrayData(400,'group question not exists'),400);
        }
        if ($data['explain']=='{}') {
            $data_qs = ['item_code' => 'question', 'group_question_id' => $data['group_question_id'], 'explain_item_id' => 0, 'sub_question_content' => $data['sub_question_content']];
            return $this->addNewData($this->model, $data_qs);
        } else {
            $data_explain = \GuzzleHttp\json_decode($data['explain'], true);
            $explain_cost = $data_explain['cost'];
            $json_text_value = \GuzzleHttp\json_encode($data_explain['explain']);
            $result = $this->addDataTranslate($json_text_value);
            $a = \GuzzleHttp\json_decode($result->content(), true);
            $code = $a['code'];
            $name_text_id = $a['metadata']['name_text_id'];
            if ($code === 400)
                return $result;

            $explain = Explain::create(['item_code' => 'question', 'explain_cost' => $explain_cost, 'explain_text_id' => $name_text_id]);
            if ($explain != null) {
                $explain_item_id = $explain->explain_item_id;

                $data_qs = ['item_code' => 'question', 'group_question_id' => $data['group_question_id'], 'explain_item_id' => $explain_item_id, 'sub_question_content' => $data['sub_question_content']];
                return $this->addNewData($this->model, $data_qs);
            } else {
                $this->deleteTextId($name_text_id);
                return response()->json($this->setArrayData(400, 'create explain error'), 400);
            }
        }
    }

    /**
     * @SWG\Put(
     *     path="/questions",
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
     *
     *     @SWG\Parameter(
     *      name = "explain",
     *     description = "explain josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"explain"},
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
    public function editQuestion(Request $request){

        $data = $request->toArray();
        $question = Question::find($data['question_id']);
        if ($question == null) {
            return response()->json($this->setArrayData(400, 'can find question'), 400);
        }
        $data_question = $request->only(['sub_question_content']);
        if ($data['explain'] == '{}') {
            $data_question['explain_item_id'] = 0;
            if($question->explain_item_id != 0) {

                $e = Explain::find($question->explain_item_id);
                if($e != null) {
                    $kq = $this->deleteTextId($e->explain_text_id);
                }
                return $this->editData($this->model, $data_question, ['question_id' => $data['question_id']]);
            }else{
                return $this->editData($this->model, $data_question, ['question_id' => $data['question_id']]);
            }

        } else {
            $data_explain = \GuzzleHttp\json_decode($data['explain'], true);
            $explain_cost = $data_explain['cost'];
            $json_text_value = \GuzzleHttp\json_encode($data_explain['explain']);
            if ($question->explain_item_id == 0) {
                //add new
                $result = $this->addDataTranslate($json_text_value);
                $a = \GuzzleHttp\json_decode($result->content(), true);
                $code = $a['code'];
                $name_text_id = $a['metadata']['name_text_id'];
                if ($code === 400)
                    return $result;

                $explain = Explain::create(['item_code' => 'question', 'explain_cost' => $explain_cost, 'explain_text_id' => $name_text_id]);
                if ($explain != null) {
                    $explain_item_id = $explain->explain_item_id;
                    $data_question['explain_item_id'] = $explain_item_id;
                    return $this->editData($this->model, $data_question, ['question_id' => $data['question_id']]);
                } else {
                    $this->deleteTextId($name_text_id);
                    return response()->json($this->setArrayData(400, 'create explain error'), 400);
                }



            } else {
                $explain = Explain::where('explain_item_id', $data_question->explain_item_id)->get()->first();

                if ($explain != null) {
                    //  DB::transaction(function () use ($explain,$data,$request){
                    $explain_text_id = $explain->explain_text_id;
                    $this->editData('App\Explain', ['explain_cost' => $explain_cost], ['explain_item_id' => $explain->explain_item_id]);
                    $result = $this->EditDataTranslate($json_text_value, $explain_text_id);

                    $a = \GuzzleHttp\json_decode($result->content(), true);
                    $code = $a['code'];
                    $name_text_id = $a['metadata']['name_text_id'];
                    if ($code === 400)
                        return $result;
                    $data_question['explain_item_id'] = $explain->explain_item_id;
                    return $this->editData($this->model, $data_question, ['question_id' => $data['question_id']]);

                    //});
                }
            }

        }



    }

    /**
     * @SWG\Delete(
     *     path="/questions/{question_id}",
     *     summary="delete group_question ",
     *     tags={"7.Question"},
     *     description="delete with question",
     *     operationId="questiondelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "question_id",
     *      description = "question_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"question_id"},
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
    public function deleteQuestion($question_id){


        $question = Question::find($question_id);
        if ($question == null) {
            return response()->json($this->setArrayData(400, 'can not find to question'), 400);
        }
        $explain_item_id = $question->explain_item_id;
        if($explain_item_id != null ) {
            $explain = Explain::where('explain_item_id', $explain_item_id)->get()->first();
            if ($explain != null) {
                TextId::destroy($explain->explain_text_id);
                $this->deleteDataById($this->model,['question_id'=>$question_id]);
                return response()->json($this->setArrayData(200, 'delete success'), 200);
            } else
                return response()->json($this->setArrayData(400, 'delete error'), 400);
        }else{
            return $this->deleteDataById($this->model,['question_id'=>$question_id]);
        }

    }
}
