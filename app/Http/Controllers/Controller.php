<?php

namespace App\Http\Controllers;

use App\Folder;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
        if ($_model == null)
            return response()->json($this->setArrayData(400, 'null', $_model->toArray()), 400);
        else
            return response()->json($this->setArrayData(200, 'null', $_model->toArray()), 200);
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

    public function addNewData($model, array $request){
        $_model = $model::create($request);
        if($_model == null){
            return response()->json($this->setArrayData(400,'add new data fail',$model),400);
        }
        return response()->json($this->setArrayData(200,'add new data success',$model),200);

    }

    public function editData($model,array $request,array $condition){
        $arr_key_cond = array_keys($condition);
        $count = count($arr_key_cond);
       // dd($request);
        if($count == 1){
            $_model = $model::where($arr_key_cond[0],$condition[$arr_key_cond[0]])->update($request);
        }else if($count == 2){
            $_model = $model::where($arr_key_cond[0],$condition[$arr_key_cond[0]])->where($arr_key_cond[1],$condition[$arr_key_cond[1]])->update($request);
        }else if($count == 3){
            $_model = $model::where($arr_key_cond[0],$condition[$arr_key_cond[0]])->where($arr_key_cond[1],$condition[$arr_key_cond[1]])->where($arr_key_cond[2],$condition[$arr_key_cond[2]])->update($request);
        }
        else{
            return 'parram condition long';
        }

        return $_model;
    }
}
