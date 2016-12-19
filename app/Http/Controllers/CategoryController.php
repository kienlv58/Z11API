<?php

namespace App\Http\Controllers;

use App\Category;
use App\Explain;
use App\Language;
use App\QueryDB;
use App\Translate;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class CategoryController extends Controller
{

    public $model = 'App\Category';



    /**
     * @SWG\Get(
     *     path="/categories/{id}",
     *     summary="get category from id",
     *     tags={"2.Category"},
     *     description="return category from id",
     *     operationId="cateid",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "id",
     *     in ="path",
     *     description = "category_id",
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
    public function getCategories($category_id)
    {

        $category = Category::find($category_id);
        if (!$category) {
            return response()->json(['code' => 404, 'status' => 'cant not find category'], 404);
        }
        $folders = $category->folder()->get();
        foreach ($folders as $folder) {
            $folder->package = $folder->package()->get();
        }
        $category->folder = $folders;
        return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $category->toArray()], 200);
    }

    /**
     * @SWG\Get(
     *     path="/categories/{limit}/{offset}",
     *     summary="get all category",
     *     tags={"2.Category"},
     *     description="return category with take and skip",
     *     operationId="cateid",
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
    public function getAllCategory($limit = 'all', $offset = 0)
    {
        if ($limit == 'all') {
            $category = Category::all();
        } else {
            $category = Category::take($limit)->skip($offset)->get();
        }
        if ($category == null)
            return response()->json(['code' => 404, 'status' => 'not found', 'metadata' => $category->toArray()], 404);
        else {
            foreach ($category as $cate) {
                $folders = $cate->folder()->get();
                foreach ($folders as $folder) {
                    $folder->package = $folder->package()->get();
                }
                $cate->folder = $folders;

            }
            return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $category->toArray()], 200);
        }


    }

    /**
     * @SWG\Post(
     *     path="/categories",
     *     summary="add new category",
     *     tags={"2.Category"},
     *     description="add new category",
     *     operationId="categoryadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "category_code",
     *      description = "category_code",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
     *    @SWG\Parameter(
     *      name = "image",
     *     description = "link image",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"image"},
     *     type = "string",
     *      )
     *     ),
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
    public function addCategory(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $kq = null;


//
        DB::transaction(function () use($request,&$kq,$user){
            function processTransaction($request,$user) {
                //Process Gateway Transaction
                $data = $request->toArray();
                $data['uid'] = $user->id;
                $myself = new CategoryController();
                $result = $myself->addDataTranslate($data['text_value']);
                $a = \GuzzleHttp\json_decode($result->content(), true);
                $code = $a['code'];
                $name_text_id = $a['metadata']['name_text_id'];
                if ($code === 400)
                    return $result;
                $result2 = $myself->addDataTranslate($data['describe_value']);
                $b = \GuzzleHttp\json_decode($result2->content(), true);
                $code2 = $b['code'];
                $describe_text_id = $b['metadata']['name_text_id'];
                if ($code2 === 400){
                    $myself->deleteTextId($name_text_id);
                    return $result2;
                }

                $data_cate = ['category_code' => $data['category_code'], 'creator_id'=>$data['uid'],'name_text_id' =>$name_text_id ,'describe_text_id'=>$describe_text_id,'image'=>$data['image']];
                return $myself->addNewData($myself->model, $data_cate);
            }
            $kq = processTransaction($request,$user);

        });
        return $kq;

    }

    /**
     * @SWG\Put(
     *     path="/categories",
     *     summary="edit a category",
     *     tags={"2.Category"},
     *     description="edit category",
     *     operationId="categoryedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *    @SWG\Parameter(
     *      name = "category_id",
     *      description = "category_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"category_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "category_code",
     *      description = "category_code",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
     *   @SWG\Parameter(
     *      name = "image",
     *     description = "link image",
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"image"},
     *     type = "string",
     *      )
     *     ),
    *   @SWG\Parameter(
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

//{"translate":[{"language_code":"vi","text_value":"thu hai","describe_value":"day la thu 2"},{"language_code":"en","text_value":"monday","describe_value":"this is monday"}]}

    public function editCategory(Request $request)
    {

        $data = $request->toArray();
        $category = Category::find($data['category_id']);
        if ($category == null) {
            return response()->json($this->setArrayData(400, 'can find category'), 400);
        }
        $name_text_id = $category->name_text_id;
        $describe_text_id = $category->describe_text_id;
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
        return $this->editData($this->model, ['category_code' => $data['category_code'],'image'=>$data['image']], ['category_id' => $data['category_id']]);


    }

    /**
     * @SWG\Delete(
     *     path="/categories/{category_code}",
     *     summary="delete category ",
     *     tags={"2.Category"},
     *     description="delete with category_id",
     *     operationId="categorydelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "category_code",
     *      description = "category_code",
     *     in ="path",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
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
     *         description="delete succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */
    public function deleteCategory($category_code )
    {
        $category = Category::where('category_code',$category_code)->get()->first();
        if ($category == null) {
            return response()->json($this->setArrayData(400, 'can not find to category code'), 400);
        }
        $name_text_id = $category->name_text_id;
        $describe_text_id = $category->describe_text_id;
        $this->deleteTextId($describe_text_id);
        return $this->deleteTextId($name_text_id);
    }


}
