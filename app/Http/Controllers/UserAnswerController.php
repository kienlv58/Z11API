<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class UserAnswerController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/useranswer",
     *     summary="add new useranswer",
     *     tags={"Useranswer"},
     *     description="add new useranswer",
     *     operationId="useranswer",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "result",
     *      description = "result json type",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"result"},
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
//{"1":{"answer_result":5,"status":true},"2":{"answer_result":7,"status":false}}
    public function addUserAnswer(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $data = $request->toArray();
        $a = \GuzzleHttp\json_decode($data['result'],true);
        foreach($a as $key=>$value){
            $this->addNewData('App\UserAnswer',['user_id'=>$user->id,'item_id'=>$key,'item_code'=>'user_answer','answer_result'=>$value['answer_result'],'status'=>$value['status'],'answer_time'=>date('Y-m-d h:i:sa')]);
        }

        return response()->json($this->setArrayData(200, 'success'), 200);
    }

    public function getUserAnswer(){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);

        $arr_answer = UserAnswer::where('user_id',$user->id)->get();

        $count_true = 0;
        $count_false = 0;
        foreach ($arr_answer as $value){
            if($value->status == true)
                $count_true++;
            if($value->status == false)
                $count_false++;

        }


    }
}
