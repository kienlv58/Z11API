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
     *     path="/package/get/{id}",
     *     summary="get package",
     *     tags={"4.Package"},
     *     description="get package with package_id",
     *     operationId="getpackage",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *     description = "package_id",
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
    public  function getPackage($id){
        $pkg = Package::find($id);
        if($pkg == null){
            return response()->json($this->setArrayData(400, 'can not find data'), 400);
        }
        else{
            $approval = $pkg->approval;
            if($approval == 0){
                return response()->json($this->setArrayData(300, 'package not yet approved'), 200);
            }
            if ($approval == 2){
                return response()->json($this->setArrayData(400, 'package not approved'), 200);
            }
            if($approval == 1){
                return response()->json($this->setArrayData(200, 'OK',$pkg), 200);
            }
    }

    }
    /**
     * @SWG\Get(
     *     path="/package/get_all/{take}/{skip}",
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
    public function getAllPackage($take = 'all',$skip = 0){
        return $this->getAllData($this->model,$take,$skip);
    }
    /**
     * @SWG\Get(
     *     path="/package/get_not_yet_aprroval/{take}/{skip}",
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
     *     path="/package/get_not_aprroval/{take}/{skip}",
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
     *     path="/package/get_aprroval/{take}/{skip}",
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
     *     path="/package/add",
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
        $data_package = $request->only(['folder_id','package_cost']);

        $check_folder = Folder::find($data_package['folder_id']);
        if($check_folder == null){
            return response()->json($this->setArrayData(400,'folder not exists'),400);
        }
        $explain_id = $this->addNewDataExplain('package',0);
        $result = $this->addDataTranslate($request->input('translate'),$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        $data_package['item_code']='package';
        $data_package['approval']=0;
        $data_package['explain_id']=$explain_id;

        return $this->addNewData($this->model,$data_package);
    }

    /**
     * @SWG\Post(
     *     path="/package/edit",
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
        $explain_id = $package->explain_id;
        $this->deleteDataTranslate($explain_id);
        $result = $this->addDataTranslate($data['translate'],$explain_id);
        $a = \GuzzleHttp\json_decode($result->content(),true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        return $this->editData($this->model,['package_cost'=>$data['package_cost']],['package_id'=>$package->package_id]);

    }
    /**
     * @SWG\Post(
     *     path="/package/delete",
     *     summary="delete package ",
     *     tags={"4.Package"},
     *     description="delete with package_id",
     *     operationId="packagedelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid delete",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "package_id",
     *      description = "package_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"package_id"},
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

    public function deletePackage(Request $request){
        $data = $request->toArray();
        $package = Package::find($data['package_id']);
        if ($package == null) {
            return response()->json($this->setArrayData(400, 'can not find to package'), 400);
        }
        $explain_id = $package->explain_id;
        return $this->deleteDataExplain($explain_id);
    }
}
