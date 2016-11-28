<?php

namespace App\Http\Controllers;

use App\Explain;
use App\Folder;
use App\Language;
use App\Translate;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
        if ($_model == null) {
            return response()->json($this->setArrayData(400, 'can not find data'), 400);
        }
        return response()->json($this->setArrayData(200, 'OK', $_model->toArray()), 200);

    }

    public function getAllData($model, $take = 'all', $skip = 0)
    {
        if ($take == 'all') {
            $_model = $model::all();
        } else {
            $_model = $model::take($take)->skip($skip)->get();
        }
        if ($_model == null || empty($_model))
            return response()->json($this->setArrayData(400, 'null', $_model->toArray()), 400);
        else
            return response()->json($this->setArrayData(200, 'OK', $_model->toArray()), 200);
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
    public function addNewDataExplain($item_code,$explain_cost){
        $explain = Explain::create(['item_code'=>$item_code,'explain_cost'=>$explain_cost]);
        return $explain->explain_id;
    }


    public function editData($model, array $request, array $condition)
    {
        $arr_key_cond = array_keys($condition);
        foreach ($request as $key => $value) {
            if ($value == null) {
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
    }
    //-================================================================================================================================================
    //=================================================================================================================================================

    //{"translate":[
//{"vi":{"text_value":"vidu", "describe_value":"day la vi du"}},
//    {"en":{"text_value":"example", "describe_value":"this is example"}}
//]}
    public function addDataTranslate($request,$explain_id)
    {

        $obj = \GuzzleHttp\json_decode($request,false);
        $arr = (object)$obj->translate;
        foreach ($arr as $key=>$value){
            if (Language::where('language_code', $value->language_code)->get()->first() == null) {
                return response()->json($this->setArrayData(400, 'language code not exits',['language_code'=>$value->language_code]), 400);
            }

        }
        foreach ($arr as $key=>$value){
                $trans = Translate::create(['explain_id'=>$explain_id,'language_code' => $value->language_code, 'text_value' => $value->text_value, 'describe_value' => $value->describe_value]);

        }

        return response()->json($this->setArrayData(200, 'add success'), 200);

    }


    public function deleteDataTranslate($explain_id)
    {
        $trans_arr = Translate::where('explain_id', $explain_id)->get();
        if ($trans_arr != null){
            foreach ($trans_arr as $trans){
                $trans->delete();
            }
            return response()->json($this->setArrayData(200,'delete success'),400);
        }
    }
    public function deleteDataExplain($explain_id){
        $result = Explain::destroy($explain_id);
        if($result){
            return response()->json($this->setArrayData(200,'delete success'),400);
        }
        else{
            return response()->json($this->setArrayData(400,'delete error'),400);
        }
    }

}
