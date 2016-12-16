<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Language;
use App\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $model ='App\Package';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\GET(
     *     path="/packages/{package_id}",
     *     summary="get package",
     *     tags={"4.Package"},
     *     description="get package with package_id",
     *     operationId="getpackage",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *     in ="path",
     *     description = "package_id",
     *     type = "integer",
     *    required = true
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
    public  function getPackage($package_id = 0){
        $pkg = Package::find($package_id);
        if($pkg == null){
            return response()->json($this->setArrayData(404, 'can not find data'), 404);
        }
        else{
            $approval = $pkg->approval;
            $chapters = $pkg->chapter()->get();
            foreach ($chapters as  $chapter){
                $chapter->groupquestion = $chapter->groupquestion()->get();
            }
            $pkg->chapters = $chapters;
            if($approval == 0){
                return response()->json($this->setArrayData(300, 'package not yet approved',$pkg), 300);
            }
            if ($approval == 2){
                return response()->json($this->setArrayData(400, 'package not approved',$pkg), 400);
            }
            if($approval == 1){
                return response()->json($this->setArrayData(200, 'OK',$pkg), 200);
            }
    }

    }
    /**
     * @SWG\Get(
     *     path="/packages/{limit}/{offset}",
     *     summary="get all package",
     *     tags={"4.Package"},
     *     description="return package with take and skip",
     *     operationId="package",
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
    public function getAllPackage($limit = 'all',$offset = 0){
        //return $this->getAllData($this->model,$take,$skip);
        if ($limit == 'all') {
            $pkg = Package::all();
        } else {
            $pkg = Package::take($limit)->skip($offset)->get();
        }
        if ($pkg == null)
            return response()->json(['code' => 404, 'status' => 'not found', 'metadata' => $pkg->toArray()], 404);
        else {
            foreach ($pkg as $package) {
                $chapters = $package->chapter()->get();

                foreach ($chapters as  $chapter){
                    $chapter->groupquestion = $chapter->groupquestion()->get();
                }
                $package->chapters = $chapters;

            }
            return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $pkg->toArray()], 200);
        }


    }
    /**
     * @SWG\Get(
     *     path="/packages/get_not_yet_aprroval/{take}/{skip}",
     *     summary="get all package",
     *     tags={"4.Package"},
     *     description="return package with take and skip",
     *     operationId="package",
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
    public function getPackageNotYetAprroval($take = 'all',$skip = 0){
        if ($take == 'all') {
            $_model = Package::where('approval',0)->get();
        } else {
            $_model = Package::where('approval',0)->take($take)->skip($skip)->get();
        }
        if ($_model == null || empty($_model))
            return response()->json($this->setArrayData(400, 'null', $_model->toArray()), 400);
        else
            return response()->json($this->setArrayData(200, 'OK', $_model->toArray()), 200);
    }

    /**
     * @SWG\Get(
     *     path="/packages/get_not_aprroval/{take}/{skip}",
     *     summary="get all package",
     *     tags={"4.Package"},
     *     description="return package with take and skip",
     *     operationId="package",
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
    public function getPackageNotAprroval($take = 'all',$skip = 0){
        if ($take == 'all') {
            $_model = Package::where('approval',2)->get();
        } else {
            $_model = Package::where('approval',2)->take($take)->skip($skip)->get();
        }
        if ($_model == null || empty($_model))
            return response()->json($this->setArrayData(400, 'null', $_model->toArray()), 400);
        else
            return response()->json($this->setArrayData(200, 'OK', $_model->toArray()), 200);
    }
    /**
     * @SWG\Get(
     *     path="/packages/get_aprroval/{take}/{skip}",
     *     summary="get all package",
     *     tags={"4.Package"},
     *     description="return package with take and skip",
     *     operationId="package",
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
    public function getPackageAprroval($take = 'all',$skip = 0){
        if ($take == 'all') {
            $_model = Package::where('approval',1)->get();
        } else {
            $_model = Package::where('approval',1)->take($take)->skip($skip)->get();
        }
        if ($_model == null || empty($_model))
            return response()->json($this->setArrayData(400, 'null', $_model->toArray()), 400);
        else
            return response()->json($this->setArrayData(200, 'OK', $_model->toArray()), 200);
    }

    /**
     * @SWG\Post(
     *     path="/packages",
     *     summary="add new package",
     *     tags={"4.Package"},
     *     description="add new package",
     *     operationId="packageadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "folder_id",
     *      description = "folder id",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"folder_id"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "text_value",
     *     description = "text_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "describe_value",
     *     description = "describe_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"describe_value"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "package_cost",
     *     description = "package_cost value",
     *      required = true,
     *      in ="formData",
     *     type = "integer",
     *
     *     @SWG\Schema(
     *     required={"package_cost"},
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */



    public function addPackage(Request $request){
        $data = $request->toArray();
        $check_folder = Folder::find($data['folder_id']);
        if($check_folder == null){
            return response()->json($this->setArrayData(400,'folder not exists'),400);
        }

        $result = $this->addDataTranslate($data['text_value']);
        $a = \GuzzleHttp\json_decode($result->content(), true);
        $code = $a['code'];
        $name_text_id = $a['metadata']['name_text_id'];
        if ($code === 400)
            return $result;
        $result2 = $this->addDataTranslate($data['describe_value']);
        $b = \GuzzleHttp\json_decode($result2->content(), true);
        $code2 = $b['code'];
        $describe_text_id = $b['metadata']['name_text_id'];
        if ($code2 === 400){
            $this->deleteTextId($name_text_id);
            return $result2;
        }

        $data_package = ['item_code' => 'package', 'folder_id'=>$data['folder_id'],'name_text_id' =>$name_text_id ,'describe_text_id'=>$describe_text_id,'approval'=>0,'package_cost'=>$data['package_cost']];
        return $this->addNewData($this->model, $data_package);
    }

    /**
     * @SWG\Put(
     *     path="/packages",
     *     summary="edit a package",
     *     tags={"4.Package"},
     *     description="edit package",
     *     operationId="packageedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *      description = "package id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"package_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "text_value",
     *     description = "text_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "describe_value",
     *     description = "describe_value josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"describe_value"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "package_cost",
     *     description = "package_cost value",
     *      required = true,
     *      in ="formData",
     *     type = "integer",
     *
     *     @SWG\Schema(
     *     required={"package_cost"},
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
     *         description="add succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function editPackage(Request $request){

        $data = $request->toArray();
        $package = Package::find($data['package_id']);
        if ($package == null) {
            return response()->json($this->setArrayData(400,'can find folder'),400);
        }
        $name_text_id = $package->name_text_id;
        $describe_text_id = $package->describe_text_id;
        if(array_key_exists('text_value',$data) == true) {
            $result = $this->EditDataTranslate($data['text_value'], $name_text_id);

            $a = \GuzzleHttp\json_decode($result->content(), true);
            $code = $a['code'];
            if ($code === 400)
                return $result;
        }
        if(array_key_exists('describe_value',$data) == true){
            $result = $this->EditDataTranslate($data['describe_value'], $describe_text_id);
            $a = \GuzzleHttp\json_decode($result->content(), true);
            $code = $a['code'];
            if ($code === 400)
                return $result;
        }
        return $this->editData($this->model,['package_cost'=>$data['package_cost']],['package_id'=>$package->package_id]);

    }
    /**
     * @SWG\Delete(
     *     path="/packages",
     *     summary="delete package ",
     *     tags={"4.Package"},
     *     description="delete with package_id",
     *     operationId="packagedelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *      description = "package_id",
     *     in ="path",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"package_id"},
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

    public function deletePackage($package_id){
        $package = Package::where('package_id',$package_id)->get()->first();
        if ($package == null) {
            return response()->json($this->setArrayData(400, 'can not find to package id'), 400);
        }
        $name_text_id = $package->name_text_id;
        $describe_text_id = $package->describe_text_id;
        $this->deleteTextId($describe_text_id);
        return $this->deleteTextId($name_text_id);
    }
}
