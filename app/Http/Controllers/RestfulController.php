<?php

namespace App\Http\Controllers;

use App\Category;
use App\Explain;
use App\Folder;
use App\Language;
use App\Package;
use App\Permission;
use App\Profile;
use App\TextId;
use App\User;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class RestfulController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/users/profile",
     *     summary="get user from id",
     *     tags={"1.User"},
     *     description="return user from id",
     *     operationId="user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
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
    public function getProfile(){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        if($user != null) {
            $profile = $user->profile()->get()->first();
            return response()->json(['code'=>200,'status'=>'OK','metadata'=>['user'=>$user,'profile'=>$profile]]);
        }else{
            return response()->json(['code'=>404,'status'=>'user not exists'],404);
        }
    }

    /**
     * @SWG\Put(
     *     path="/users/profile",
     *     summary="edit profile ",
     *     tags={"1.User"},
     *     description="edit with profile_id",
     *     operationId="profileedit",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "name",
     *      description = "name",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name"},
     *     type = "string"
     *      )
     *           ),
     *      @SWG\Parameter(
     *      name = "image",
     *      description = "image",
     *     in ="formData",
     *     type="string",
     *     @SWG\Schema(
     *     required={"image"},
     *     type = "string"
     *      )
     *           ),
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
    public function editProfile(Request $request){
        $data = $request->toArray();
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        if($user == null)
            return response()->json(['code'=>404,'status'=>'user not exists'],404);
        return $this->editData('App\Profile',$data,['user_id'=>$user->id]);

    }
    /**
     * @SWG\Put(
     *     path="/users/chargecoin",
     *     summary="chargecoin",
     *     tags={"1.User"},
     *     description="chargecoin",
     *     operationId="chargecoin",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "coin",
     *      description = "coin charge",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"name"},
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
    public function chargeCoin(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $data = $request->toArray();
        $data['user_id'] = $user->id;
        $profile = Profile::where('user_id',$data['user_id'])->get()->first();
        if($profile == null)
            return response()->json(['code'=>404,'status'=>'user not exists'],404);
        else{
            $old_coin = $profile->coin;
            $new_coin = $old_coin + $data['coin'];
            $profile->update(['coin'=>$new_coin]);
            return response()->json(['code'=>200,'status'=>'charge success','coin current'=>$new_coin],200);
        }
    }

    public function generateDB(){
        DB::table('permissions')->truncate();
        $arr_code = ['category','folder','package','chapter','group_question','question','answer','pucharse','admin'];
        $arr_path = ['categories','folders','packages','chapters','group_questions','questions','answers','pucharses','admin'];

        for($i = 0; $i < count($arr_code);$i++){
            $path = 'api/v1/'.$arr_path[$i];
            $code_get = 'get_'.$arr_code[$i];
            $code_post = 'add_'.$arr_code[$i];
            $code_put = 'edit_'.$arr_code[$i];
            $code_delete = 'delete_'.$arr_code[$i];
            Permission::create(['method'=>'GET','path'=>$path,'permission_code'=>$code_get,'description'=>'get '.$arr_code[$i]]);
            Permission::create(['method'=>'POST','path'=>$path,'permission_code'=>$code_post,'description'=>'add '.$arr_code[$i]]);
            Permission::create(['method'=>'PUT','path'=>$path,'permission_code'=>$code_put,'description'=>'edit '.$arr_code[$i]]);
            Permission::create(['method'=>'DELETE','path'=>$path,'permission_code'=>$code_delete,'description'=>'delete '.$arr_code[$i]]);
        }
        return 'success';
    }
    /**
     * @SWG\Get(
     *     path="/language",
     *     summary="language",
     *     tags={"Language"},
     *     description="language",
     *     operationId="language",
     *     consumes={"application/json"},
     *     produces={"application/json"},
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
    public function getLanguage(){
        $language = Language::all();
        if(count($language)  == 0)
            return response()->json(['code'=>400,'status'=>'no any language'],400);
        return response()->json(['code'=>200,'status'=>'success',"listlanguage"=>$language->toArray()],200);
    }
    /**
     * @SWG\Post(
     *     path="/language",
     *     summary="language",
     *     tags={"Language"},
     *     description="language",
     *     operationId="language",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "language_code",
     *      description = "language_code",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name"},
     *     type = "string"
     *      )
     *           ),
     *  @SWG\Parameter(
     *      name = "description",
     *      description = "description",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name"},
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
    public function addLanguage(Request $request){
        $data = $request->toArray();
        $find = Language::where('language_code',$data['language_code'])->get()->first();
        if($find != null)
            return response()->json(['code'=>400,'status'=>'language exists'],400);
        return $this->addNewData('App\Language',['description'=>$data['description'],'language_code'=>$data['language_code'],'item_code'=>'language']);
    }
    public function test(){
        $arr_item_id = TextId::all();
        if(count($arr_item_id) >0){
            foreach($arr_item_id as $value){
                if(Category::where('name_text_id',$value->text_id)->orWhere('describe_text_id',$value->text_id)->get()->first() != null)
                    continue;
                if(Folder::where('name_text_id',$value->text_id)->orWhere('describe_text_id',$value->text_id)->get()->first() != null)
                    continue;
                if(Package::where('name_text_id',$value->text_id)->orWhere('describe_text_id',$value->text_id)->get()->first() != null)
                    continue;
                if(Explain::where('explain_text_id',$value->text_id)->get()->first() != null)
                    continue;
                $status = $value->delete();
            }
        }
    }

}
