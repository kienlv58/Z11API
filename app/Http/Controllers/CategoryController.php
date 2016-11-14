<?php

namespace App\Http\Controllers;

use App\Category;
use App\Explain;
use App\QueryDB;
use App\Translate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $model = 'App\Category';
    /**
     * @SWG\Get(
     *     path="/get_category/{id}",
     *     summary="get category from id",
     *     tags={"Category"},
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
            return response()->json(['code' => 400, 'status' => 'cant not find category'], 400);
        }
        return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $category->toArray()], 200);
    }

    /**
     * @SWG\Get(
     *     path="/get_all_category/{take}/{skip}",
     *     summary="get all category",
     *     tags={"Category"},
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
            return response()->json(['code' => 200, 'status' => 'OK', 'metadata' => $category->toArray()], 200);
        else
            return response()->json(['code' => 200, 'status' => 'null', 'metadata' => $category->toArray()], 200);

    }

    /**
     * @SWG\Post(
     *     path="/admin/add_category",
     *     summary="add new category",
     *     tags={"Category"},
     *     description="add new category",
     *     operationId="categoryadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid create",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "category_code",
     *      description = "category code",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "text_value(en)",
     *     description = "english of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value(en)"},
     *     type = "string",
     *      )
     *     ),
     *      @SWG\Parameter(
     *      name = "desctiption(en)",
     *     description = "desctiption english of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"desctiption(en)"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "text_value(vi)",
     *     description = "vietnam of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *     @SWG\Schema(
     *     required={"text_value(vi)"},
     *     type = "string"
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "desctiption(vi)",
     *     description = "desctiption vietnam of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"desctiption(vi)"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "explain_cost",
     *     description = "cost of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *     @SWG\Schema(
     *     required={"explain_cost"},
     *     type = "string"
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
        $explain = new Explain();
        $explain->item_code = 'data_explain_item';
        $explain->explain_cost = $data['explain_cost'];
        $explain->save();
        $name_text_id = $explain->explain_id;

        $translate_vi = new Translate();
        $translate_vi->name_text_id = $name_text_id;
        $translate_vi->language_id = 'vi';
        $translate_vi->text_value = $data['text_value(vi)'];
        $translate_vi->describe_value = $data['desctiption(vi)'];
        $translate_vi->save();
        $translate_en = Translate::create(['name_text_id' => $name_text_id, 'language_id' => 'en', 'text_value' => $data['text_value(en)'], 'describe_value' => $data['desctiption(en)']]);

        $categpry = Category::create(['category_code' => $data['category_code'], 'name_text_id' => $name_text_id]);
        return response()->json(['code' => 200, 'status' => 'add success', 'metadata' => $categpry->toArray()]);
    }

    /**
     * @SWG\Post(
     *     path="/admin/edit_category",
     *     summary="add new category",
     *     tags={"Category"},
     *     description="edit category",
     *     operationId="categoryedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid create",
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
     *     @SWG\Parameter(
     *      name = "category_code",
     *      description = "category code",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"category_code"},
     *     type = "string"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "text_value(en)",
     *     description = "english of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value(en)"},
     *     type = "string",
     *      )
     *     ),
     *      @SWG\Parameter(
     *      name = "desctiption(en)",
     *     description = "desctiption english of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"desctiption(en)"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "text_value(vi)",
     *     description = "vietnam of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *     @SWG\Schema(
     *     required={"text_value(vi)"},
     *     type = "string"
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "desctiption(vi)",
     *     description = "desctiption vietnam of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"desctiption(vi)"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "explain_cost",
     *     description = "cost of value",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *     @SWG\Schema(
     *     required={"explain_cost"},
     *     type = "string"
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
    public function editCategory(Request $request)
    {
        $data = $request->toArray();
        $cate = Category::find($data['category_id']);
        if($cate == null){
            return response()->json(['code' => 400, 'status' => 'cant not find category', 'metadata' => []]);
        }
        $name_text_id = $cate->name_text_id;
        if ($data['text_value(vi)'] != null)
            Translate::where('name_text_id', $name_text_id)->where('language_id', 'vi')->update(['text_value'=>$data['text_value(vi)'],'describe_value'=>$data['desctiption(vi)']]);
        if($data['text_value(en)'] != null)
            Translate::where('name_text_id', $name_text_id)->where('language_id', 'en')->update(['text_value'=>$data['text_value(en)'],'describe_value'=>$data['desctiption(en)']]);

        if($data['explain_cost'] != null){
            Explain::where('explain_id',$name_text_id)->update(['explain_cost'=>$data['explain_cost']]);
        }

        if($data['category_code'] != null){
            Category::where('category_id',$name_text_id)->update(['category_code'=>$data['category_code']]);
        }
        $category = Category::find($name_text_id);
        $category->explain_cost = $category->explain()->select('explain_cost')->get();
        $category->translate = $category->translate()->select('language_id','text_value','describe_value')->get();
        return response()->json(['code'=>200,'status'=>'update success','metadata'=>$category->toArray()]);


    }
    /**
     * @SWG\Post(
     *     path="/admin/delete_category",
     *     summary="delete category ",
     *     tags={"Category"},
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
    public function deleteCategory(Request $request){
//        $data = $request->toArray();
//        //$cate = Category::find($data['category_id']);
//        $queryDB = new QueryDB();
//       $result = $queryDB->deteleDBWithID('explains','explain_id',$data['category_id']);
//        if($result){
//            return response()->json(['code'=>200,'status'=>'delete succesfull']);
//        }else
//            return response()->json(['code'=>400,'status'=>'can not find category']);
        return $this->deleteDataById($this->model,$request->toArray());
    }

    public function test(Request $request){
        //return $this->deleteDataById('App\Explain',$request->toArray());
        return $this->editData('App\Explain',$request->toArray(),['explain_id'=>6,'explain_cost'=>20]);
    }

}
