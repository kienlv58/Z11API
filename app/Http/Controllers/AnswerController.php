<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    protected $model = 'App\Answer';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function getAnswer($id)
    {
        $answer = Answer::where('question_id', $id)->get();
        if ($answer == null || sizeof($answer) == 0)
            return response()->json($this->setArrayData(400, 'not found answer'), 400);
        else
            return response()->json($this->setArrayData(200, 'OK', $answer->toArray()), 200);

    }

    /**
     * @SWG\Get(
     *     path="/answers/{limit}/{offset}",
     *     summary="get all answer",
     *     tags={"8.Answer"},
     *     description="return answer with take and skip",
     *     operationId="answer",
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
    public function getAllAnswer($limit = 'all', $offset = 0)
    {
        return $this->getAllData($this->model, $limit, $offset);
    }

    /**
     * @SWG\Post(
     *     path="/answers",
     *     summary="add new answer",
     *     tags={"8.Answer"},
     *     description="add new answer",
     *     operationId="answer_add",
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
     *      name = "answer",
     *      description = "answer type json",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"answer"},
     *     type = "string"
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
//{"answer":[{"answer_item_value":"this is answer number 1","answer_is_correct":1},{"answer_item_value":"this is answer number 2","answer_is_correct":0}]}
    public function addAnswer(Request $request)
    {
        $data_answer = $request->toArray();
        $arr = \GuzzleHttp\json_decode($request->input('answer'),false);
        $arr_obj = (object)$arr->answer;
        foreach ($arr_obj as $key=>$value){
            $answer = Answer::create(['item_code'=>'answer','question_id'=>$data_answer['question_id'],'answer_item_value'=>$value->answer_item_value,'answer_is_correct'=>$value->answer_is_correct]);

        }

        return response()->json($this->setArrayData(400, 'add success'), 200);


    }

    /**
     * @SWG\Put(
     *     path="/answers",
     *     summary="edit answer",
     *     tags={"8.Answer"},
     *     description="edit answer",
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
     *
     *     @SWG\Parameter(
     *      name = "answer",
     *      description = "answer type json",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"answer"},
     *     type = "string"
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function editAnswer(Request $request)
    {

        $data_answer = $request->toArray();
        $arr = \GuzzleHttp\json_decode($request->input('answer'),false);
        $arr_obj = (object)$arr->answer;
        $answer_arr = Answer::where('question_id', $data_answer['question_id'])->get();
        if ($answer_arr != null) {
            foreach ($answer_arr as $answer) {
                $answer->delete();
            }
            foreach ($arr_obj as $key => $value) {
                $answer = Answer::create(['item_code' => 'answer', 'question_id' => $data_answer['question_id'], 'answer_item_value' => $value->answer_item_value, 'answer_is_correct' => $value->answer_is_correct]);

            }
            return response()->json($this->setArrayData(200,'edit success'),200);
        }
        else
            return response()->json($this->setArrayData(400,'edit error'),400);

    }

    /**
     * @SWG\Delete(
     *     path="/answers",
     *     summary="delete answer ",
     *     tags={"8.Answer"},
     *     description="delete with answer_id",
     *     operationId="answerdelete",
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
     *      name = "answer_item_id",
     *      description = "answer_item_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"answer_item_id"},
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
    public function deleteAnswer($answer_item_id)
    {
        $answer = Answer::find($answer_item_id);
        if ($answer == null) {
            return response()->json($this->setArrayData(400, 'can not find to answer'), 400);
        }
        return $this->deleteDataById($this->model, ['answer_item_id' => $answer_item_id]);
    }
}
