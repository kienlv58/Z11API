<?php

namespace App\Http\Controllers;

use App\Category;
use App\Explain;
use App\Language;
use App\QueryDB;
use App\Translate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $model = 'App\Category';

    /**
     * @SWG\Get(
     *     path="/category/get/{id}",
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
    public function getCategory($id = 0)
    {
        $category = Category::find($id);
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
     *     path="/category/get_all/{take}/{skip}",
     *     summary="get all category",
     *     tags={"2.Category"},
     *     description="return category with take and skip",
     *     operationId="cateid",
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
    public function getAllCategory($take = 'all', $skip = 0)
    {
        if ($take == 'all') {
            $category = Category::all();
        } else {
            $category = Category::take($take)->skip($skip)->get();
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
     *     path="/admin/add_category",
     *     summary="add new category",
     *     tags={"2.Category"},
     *     description="add new category",
     *     operationId="categoryadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *    @SWG\Parameter(
     *      name = "uid",
     *      description = "uid edit",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"uid_id"},
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
     *      name = "translate",
     *     description = "translate josn",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"translate"},
     *     type = "string",
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
    public function addCategory(Request $request)
    {


        $data = $request->toArray();
        $explain_id = $this->addNewDataExplain('category', 0);
        $result = $this->addDataTranslate($data['translate'], $explain_id);
        $a = \GuzzleHttp\json_decode($result->content(), true);
        $code = $a['code'];
        if ($code === 400)
            return $result;
        $data_cate = ['category_code' => $data['category_code'], 'explain_id' => $explain_id,'image'=>$data['image']];
        return $this->addNewData($this->model, $data_cate);
    }

    /**
     * @SWG\Post(
     *     path="/admin/edit_category",
     *     summary="edit a category",
     *     tags={"2.Category"},
     *     description="edit category",
     *     operationId="categoryedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid edit",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"uid_id"},
     *     type = "integer"
     *      )
     *           ),
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
        $explain_id = $category->explain_id;
        $this->deleteDataTranslate($explain_id);
        $result = $this->addDataTranslate($data['translate'], $explain_id);
        $a = \GuzzleHttp\json_decode($result->content(), true);
        $code = $a['code'];
        if ($code === 400)
            return $result;

        return $this->editData($this->model, ['category_code' => $data['category_code'],'image'=>$data['image']], ['category_id' => $category->category_id]);


    }

    /**
     * @SWG\Post(
     *     path="/admin/delete_category",
     *     summary="delete category ",
     *     tags={"2.Category"},
     *     description="delete with category_id",
     *     operationId="categorydelete",
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
    public function deleteCategory(Request $request)
    {
        $data = $request->toArray();
        $category = Category::find($data['category_id']);
        if ($category == null) {
            return response()->json($this->setArrayData(400, 'can not find to category'), 400);
        }
        $explain_id = $category->explain_id;
        return $this->deleteDataExplain($explain_id);
    }


}
