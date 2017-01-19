<?php

namespace App\Http\Controllers;

use App\Explain;
use App\GroupQuestion;
use App\Question;
use App\Translate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use phpDocumentor\Reflection\Types\Object_;

class ExplainController extends Controller
{

    //get trainslate

    public function getTranslateFromExplain($groupQS_id){
        $user = JWTAuth::parseToken()->authenticate();
        //$user = User::findOrFail($user->id);
        $groupqs = GroupQuestion::find($groupQS_id);

        $translate_data = new Object_();
        if($groupqs != null){
            $explain_id = $groupqs->explain_item_id;
            $explain_model = Explain::find($explain_id);
            $arr_translate_group = Translate::where('text_id',$explain_model->explain_text_id)->get();
            $translate_data->translate_group = $arr_translate_group;
            $translate_qs = [];

            $arr_qs = Question::where("group_question_id",$groupQS_id)->get();
            $i = 0;
            if(count($arr_qs) > 0){
                foreach ($arr_qs as $value){
                    $exp_id = $value->explain_item_id;
                    $exp_model = Explain::find($exp_id);
                    $name_text_id = $exp_model->explain_text_id;
                    $arr_translate_qs = Translate::where('text_id',$name_text_id)->get();
                    $translate_qs[$i] = $arr_translate_qs;

                }
            }
            $translate_data->subquestion = $translate_qs;
            return response()->json(['code'=>200,'status'=>"success",$translate_data],200);

        }else
            return response()->json(['code'=>400,'status'=>"not found"],400);

    }
}
