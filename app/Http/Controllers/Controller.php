<?php

namespace App\Http\Controllers;

use App\Category;
use App\Explain;
use App\Folder;
use App\Language;
use App\TextId;
use App\Translate;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Object_;

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="localhost:8000",
 *     basePath="/api/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Main Z11 api",
 *         description="This is our main Z11 api. It contains all method to handle Z11 project...",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="kienlv58@gmail.com"
 *         ),
 *         @SWG\License(
 *             name="Private License",
 *             url="URL to the license"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about this in our FAQ",
 *         url="http://www.google.de"
 *     )
 * )
 */
class Controller extends BaseController
{


    //https://medium.com/@mahbubkabir/discovering-swagger-in-laravel-rest-apis-cb0271c8f2#.diepg499a
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setArrayData($code, $status, $metadata = null)
    {
        if ($metadata == null) {
            return ['code' => $code, 'status' => $status];
        }
        return ['code' => $code, 'status' => $status, 'metadata' => $metadata];
    }

    public function getDataById($model, $id = 0)
    {
        $_model = $model::find($id);
        if ($model == 'App\User') {
            $_model->profile = $_model->profile()->get()->first();
            $_model->type_user = $_model->userrole()->get()->first();
        }
        if ($_model == null) {
            return response()->json($this->setArrayData(400, 'can not find data'), 400);
        }
        return response()->json($this->setArrayData(200, 'OK', $_model->toArray()), 200);

    }

    public function getAllData($model, $take = 'all', $skip = 0)
    {
        if ($take == 'all') {
            $_model = $model::all();
            $array = array();
            // $array =$_model;
            foreach ($_model as $value) {
                if ($model == 'App\User') {
                    $profile = $value->profile()->get()->first();
                    $value->profile = $profile;
                    $type_user = $value->userrole()->get()->first();
                    $value->type_user = $type_user;
                }
                if ($model == 'App\Folder') {
                    $category = $value->category()->get()->first();
                    $packages = $value->package()->get()->first();
                    $value->category = $category;
                    $value->package = $packages; 
                }
                if ($model == 'App\Chapter') {
                    $package = $value->package()->get()->first();
                    $groupquestion =  $value->groupquestion()->get()->first();
                    $value->package = $package;
                    $value->groupquestion = $groupquestion;
                }
                $array[] = $value;
            }
            // $_model->profile = $_model->profile()->get()->first();
            // $_model->type_user = $_model->userrole()->get()->first();

        } else {
            $_model = $model::take($take)->skip($skip)->get();
        }
        if ($array == null || empty($array))
            return response()->json($this->setArrayData(400, 'null', $array), 400);
        else
            return response()->json($this->setArrayData(200, 'OK', $array), 200);
    }

    public function deleteDataById($model, array $request)
    {
        $m = new $model;
        $primaryKey = $m->primaryKey;
        $_model = $model::find($request[$primaryKey]);
        if ($_model == null) {
            return response()->json($this->setArrayData(400, 'can not find data'), 400);
        } else {
            $_model->delete();
            return response()->json($this->setArrayData(200, 'delete success'), 200);
        }
    }

    public function addNewData($model, array $request)
    {
        $_model = $model::create($request);
        if ($_model == null) {
            return response()->json($this->setArrayData(400, 'add new data fail'), 400);
        }
        return response()->json($this->setArrayData(200, 'add new data success', $_model), 200);

    }

    public function addNewDataExplain($item_code, $explain_cost)
    {
        $explain = Explain::create(['item_code' => $item_code, 'explain_cost' => $explain_cost]);
        return $explain->explain_id;
    }


    public function editData($model, array $request, array $condition)
    {

       // DB::transaction(function () use ($model,$request,$condition) {
            $arr_key_cond = array_keys($condition);
            foreach ($request as $key => $value) {
                if ($value === null) {
                    unset($request[$key]);
                }
            }

            $count = count($arr_key_cond);

            if ($count == 1) {
                $_model = $model::where($arr_key_cond[0], $condition[$arr_key_cond[0]])->update($request);
            } else if ($count == 2) {
                $_model = $model::where($arr_key_cond[0], $condition[$arr_key_cond[0]])->where($arr_key_cond[1], $condition[$arr_key_cond[1]])->update($request);
            } else if ($count == 3) {
                $_model = $model::where($arr_key_cond[0], $condition[$arr_key_cond[0]])->where($arr_key_cond[1], $condition[$arr_key_cond[1]])->where($arr_key_cond[2], $condition[$arr_key_cond[2]])->update($request);
            } else {
                return 'parram condition long';
            }
            if ($_model == 1)
                return response()->json($this->setArrayData(200, 'edit successfull'), 200);
            else
                return response()->json($this->setArrayData(400, 'something errors'), 400);
       // });
    }
    //-================================================================================================================================================
    //=================================================================================================================================================

// {"vi": "Tieng anh", "en": "English", "jp":"abc"}
//{"vi": "Hoc tieng anh", "en": "Learning english", "jp":"abc xyz"}
    public  function addDataTranslate($text_value_json, $text_id = null)
    {
        $arr_text_value = \GuzzleHttp\json_decode($text_value_json, true);
        foreach ($arr_text_value as $key => $value) {
            if (Language::where('language_code', $key)->get()->first() == null) {
                return response()->json($this->setArrayData(400, 'language code not exits', ['language_code' => $key,'name_text_id'=>null]), 400);
            }

        }

        if ($text_id == null) {
            $text_ids = TextId::create([]);
            $text_id = $text_ids->text_id;
        }
        foreach ($arr_text_value as $key => $value) {
            $trans = Translate::create(['text_id' => $text_id, 'language_code' => $key, 'text_value' => $value]);
        }

        return response()->json($this->setArrayData(200, 'add success',['name_text_id'=>$text_id]), 200);


    }


    public function EditDataTranslate($text_value_json,$text_id)
    {
        $arr_text_value = \GuzzleHttp\json_decode($text_value_json, true);
        foreach ($arr_text_value as $key => $value) {
            if (Language::where('language_code', $key)->get()->first() == null) {
                return response()->json($this->setArrayData(400, 'language code not exits', ['language_code' => $key,'name_text_id'=>null]), 400);
            }

        }
        $trans_arr = Translate::where('text_id', $text_id)->get();
        if ($trans_arr != null) {
            foreach ($trans_arr as $trans) {
                $trans->delete($trans->translate_id);
            }
            foreach ($arr_text_value as $key => $value) {
                $trans = Translate::create(['text_id' => $text_id, 'language_code' => $key, 'text_value' => $value]);
            }
            return response()->json($this->setArrayData(200, 'edit success',['name_text_id'=>$text_id]), 200);

        }
    }

    public function deleteDataExplain($explain_id)
    {
        $result = Explain::destroy($explain_id);
        if ($result) {
            return response()->json($this->setArrayData(200, 'delete success'), 400);
        } else {
            return response()->json($this->setArrayData(400, 'delete error'), 400);
        }
    }

    public function deleteTextId($text_id){
        $result = TextId::destroy($text_id);
        if ($result) {
            return response()->json($this->setArrayData(200, 'delete success'), 400);
        } else {
            return response()->json($this->setArrayData(400, 'delete error'), 400);
        }
    }

    public function getTranslate($text_id){
        $translate = Translate::where('text_id',$text_id)->get();
        if($translate == null)
            return null;
        return $translate->toArray();
    }


}
