<?php

namespace App\Http\Controllers;

use App\Chapter;
use App\Explain;
use App\GroupQuestion;
use App\Language;
use App\TextId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupQuestionController extends Controller
{
    protected $model = 'App\GroupQuestion';


    /**
     * @SWG\Get(
     *     path="/group_questions/{group_question_id}",
     *     summary="get category from id",
     *     tags={"6.GroupQuestion"},
     *     description="return category from id",
     *     operationId="cateid",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "group_question_id",
     *     in ="path",
     *     description = "group_question_id",
     *     required = true,
     *     type = "integer"
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
     *         description="cant not find category",
     *     )
     * )
     */

    public function getGroupQuestion($group_question_id)
    {
        $groups = GroupQuestion::find($group_question_id);
        if (!$groups) {
            return response()->json(['code' => 404, 'status' => 'cant not find groupquestion'], 404);
        }
        $questions = $groups->question()->get();
        foreach ($questions as $question) {
            $question->answers = $question->answer()->get();
        }
        $groups->questions = $questions;
        return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $groups->toArray()], 200);

    }

    /**
     * @SWG\Get(
     *     path="/group_questions/{limit}/{offset}",
     *     summary="get all group_question",
     *     tags={"6.GroupQuestion"},
     *     description="return group_question with take and skip",
     *     operationId="group_question",
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
    public function getAllGroupQuestion($limit = 'all', $offset = 0)
    {
        if ($limit == 'all') {
            $groups = GroupQuestion::all();
        } else {
            $groups = GroupQuestion::take($limit)->skip($offset)->get();
        }
        if (count($groups) == 0)
            return response()->json(['code' => 404, 'status' => 'not found', 'metadata' => $groups->toArray()], 404);
        else {
            foreach ($groups as $group) {
                $questions = $group->question()->get();
                foreach ($questions as $question) {
                    $question->answers = $question->answer()->get();
                }
                $group->questions = $questions;
                return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $groups->toArray()], 200);

            }
        }
    }

    /**
     * @SWG\Post(
     *     path="/group_questions",
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
     *
     *     @SWG\Parameter(
     *      name = "explain",
     *     description = "explain josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value"},
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

    //{“cost”: 8, “explain”: { “vi”:”la chu ngu”, “en”:” is subject” }}
    public function addGroupQuestion(Request $request)
    {


        $data = $request->toArray();
        $check_chapter = Chapter::find($data['chapter_id']);
        if ($check_chapter == null) {
            return response()->json($this->setArrayData(400, 'chapter not exists'), 400);
        }
        if ($data['explain']  == '{}') {
            $data_group_qs = ['item_code' => 'groupquestion', 'chapter_id' => $data['chapter_id'], 'explain_item_id' => 0, 'group_question_content' => $data['group_question_content'], 'group_question_transcript' => $data['group_question_transcript'], 'group_question_image' => $data['group_question_image'], 'group_question_audio' => $data['group_question_audio']];
            return $this->addNewData($this->model, $data_group_qs);
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

            $explain = Explain::create(['item_code' => 'groupquestion', 'explain_cost' => $explain_cost, 'explain_text_id' => $name_text_id]);
            if ($explain != null) {
                $explain_item_id = $explain->explain_item_id;

                $data_group_qs = ['item_code' => 'groupquestion', 'chapter_id' => $data['chapter_id'], 'explain_item_id' => $explain_item_id, 'group_question_content' => $data['group_question_content'], 'group_question_transcript' => $data['group_question_transcript'], 'group_question_image' => $data['group_question_image'], 'group_question_audio' => $data['group_question_audio']];
                return $this->addNewData($this->model, $data_group_qs);
            } else {
                $this->deleteTextId($name_text_id);
                return response()->json($this->setArrayData(400, 'create explain error'), 400);
            }
        }

        //DB::transaction(function () use ($data,$kq){


        //});
    }

    /**
     * @SWG\Put(
     *     path="/group_questions",
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
    public function editGroupQuestion(Request $request)
    {

        $data = $request->toArray();
        $group_qs = GroupQuestion::find($data['group_question_id']);
        if ($group_qs == null) {
            return response()->json($this->setArrayData(400, 'can find group question'), 400);
        }
        $data_group = $request->only(['group_question_content', 'group_question_transcript', 'group_question_image', 'group_question_audio']);

        if ($data['explain'] == '{}') {
            $data_group['explain_item_id'] =0;

            if($group_qs->explain_item_id != 0){
                $e = Explain::find($group_qs->explain_item_id);
                if($e != null) {
                    $kq = $this->deleteTextId($e->explain_text_id);
                }

                //dd($data_group);
                return $this->editData($this->model, $data_group, ['group_question_id' => $data['group_question_id']]);
            }else{
                return $this->editData($this->model, $data_group, ['group_question_id' => $data['group_question_id']]);
            }

        } else {
            $data_explain = \GuzzleHttp\json_decode($data['explain'], true);
            $explain_cost = $data_explain['cost'];
            $json_text_value = \GuzzleHttp\json_encode($data_explain['explain']);
            if ($group_qs->explain_item_id == 0) {
            //add new
                $result = $this->addDataTranslate($json_text_value);
                $a = \GuzzleHttp\json_decode($result->content(), true);
                $code = $a['code'];
                $name_text_id = $a['metadata']['name_text_id'];
                if ($code === 400)
                    return $result;

                $explain = Explain::create(['item_code' => 'groupquestion', 'explain_cost' => $explain_cost, 'explain_text_id' => $name_text_id]);
                if ($explain != null) {
                    $explain_item_id = $explain->explain_item_id;
                    $data_group['explain_item_id'] = $explain_item_id;
                    return $this->editData($this->model, $data_group, ['group_question_id' => $data['group_question_id']]);
                } else {
                    $this->deleteTextId($name_text_id);
                    return response()->json($this->setArrayData(400, 'create explain error'), 400);
                }



            } else {
                $explain = Explain::where('explain_item_id', $group_qs->explain_item_id)->get()->first();

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
                    $data_group['explain_item_id'] = $explain->explain_item_id;
                    return $this->editData($this->model, $data_group, ['group_question_id' => $data['group_question_id']]);

                    //});
                }
            }

        }


    }

    /**
     * @SWG\Delete(
     *     path="/group_questions/{group_question_id}",
     *     summary="delete group_question ",
     *     tags={"6.GroupQuestion"},
     *     description="delete with group_question",
     *     operationId="chapterdelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "group_question_id",
     *      description = "group_question_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"group_question_id"},
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
    public function deleteGroupQuestion($group_question_id)
    {
        $group_qs = GroupQuestion::find($group_question_id);
        if ($group_qs == null) {
            return response()->json($this->setArrayData(400, 'can not find to group question'), 400);
        }
        $explain_item_id = $group_qs->explain_item_id;
        if($explain_item_id != 0 ){
            $explain = Explain::where('explain_item_id', $explain_item_id)->get()->first();
            if ($explain != null) {
                TextId::destroy($explain->explain_text_id);
                $this->deleteDataById($this->model,['group_question_id'=>$group_question_id]);
                return response()->json($this->setArrayData(200, 'delete success'), 200);
            } else
                return response()->json($this->setArrayData(400, 'delete error'), 400);
        }else{
            return $this->deleteDataById($this->model,['group_question_id'=>$group_question_id]);
        }

    }
}
