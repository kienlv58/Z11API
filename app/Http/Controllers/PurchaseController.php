<?php

namespace App\Http\Controllers;

use App\Explain;
use App\Package;
use App\Profile;
use App\purchase;
use App\User;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $model = 'App\purchase';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\GET(
     *     path="/purchase/get_user/{user_id}",
     *     summary="get_user",
     *     tags={"Purchase"},
     *     description="get_user purchas with user_id",
     *
     *     operationId="get_user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *     description = "user_id",
     *      required = true,
     *     default = 0,
     *      in ="path",
     *     type = "integer",
     *
     *     @SWG\Schema(
     *     required={"user_id"},
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

    public function getUserPurchase($user_id = 0)
    {
        if ($user_id == 0) {
            return response()->json($this->setArrayData(400, 'can not find user'), 400);
        }
        $arr_purchase = purchase::where('user_id', $user_id)->get();
        return response()->json($this->setArrayData(200, 'success fully', $arr_purchase->toArray()), 200);
    }

    /**
     * @SWG\GET(
     *     path="/purchase/getUserPurchase_package/{user_id}",
     *     summary="getUserPurchase_package",
     *     tags={"Purchase"},
     *     description="getUserPurchase_package purchas with user_id",
     *
     *     operationId="get_user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *     description = "user_id",
     *      required = true,
     *      in ="path",
     *     default=0,
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
    public function getUserPurchase_package($user_id = 0)
    {
        if ($user_id == 0) {
            return response()->json($this->setArrayData(400, 'can not find user'), 400);
        }
        $arr_purchase = purchase::where('user_id', $user_id)->where('item_code', 'package')->get();
        return response()->json($this->setArrayData(200, 'success fully', $arr_purchase->toArray()), 200);
    }

    /**
     * @SWG\GET(
     *     path="/purchase/getUserPurchase_explain/{user_id}",
     *     summary="getUserPurchase_explain",
     *     tags={"Purchase"},
     *     description="getUserPurchase_explain purchas with user_id",
     *
     *     operationId="get_user",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *     description = "user_id",
     *      required = true,
     *      in ="path",
     *      default = 0,
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

    public function getUserPurchase_explain($user_id = 0)
    {
        if ($user_id == 0) {
            return response()->json($this->setArrayData(400, 'can not find user'), 400);
        }
        $arr_purchase = purchase::where('user_id', $user_id)->where('item_code', 'explain')->get();
        return response()->json($this->setArrayData(200, 'success fully', $arr_purchase->toArray()), 200);
    }

    /**
     * @SWG\GET(
     *     path="/purchase/getPurchaseId/{id}",
     *     summary="getPurchaseId",
     *     tags={"Purchase"},
     *     description="getPurchaseId purchas with purchase_id",
     *
     *     operationId="getPurchaseId",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "purchase_id",
     *     description = "purchase_id",
     *      required = true,
     *      in ="path",
     *     default = 0,
     *     type = "integer",
     *
     *     @SWG\Schema(
     *     required={"purchase_id"},
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

    public function getPurchaseId($id = 0)
    {
        if ($id == 0) {
            return response()->json($this->setArrayData(400, 'can not find purchase'), 400);
        }
        $purchase = purchase::find($id);
        if($purchase == null)
            return response()->json($this->setArrayData(400, 'can not find purchase'), 400);
        return response()->json($this->setArrayData(200, 'success fully', $purchase->toArray()), 200);
    }

    /**
     * @SWG\Get(
     *     path="/purchase/get_all/{take}/{skip}",
     *     summary="get all Chapter",
     *     tags={"Purchase"},
     *     description="return purchase with take and skip",
     *     operationId="purchase",
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
    public function getAllPurchase($take = 'all', $skip = 0)
    {
        return $this->getAllData($this->model, $take, $skip);
    }

    //user_id,item_id,item_code
    /**
     * @SWG\Post(
     *     path="/purchase/add_payment",
     *     summary="add new purchase",
     *     tags={"Purchase"},
     *     description="add new purchase",
     *     operationId="purchaseadd",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "user_id",
     *      description = "user_id ",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"user_id"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "item_id",
     *     description = "item_id of value",
     *      required = true,
     *      in ="formData",
     *     type = "integer",
     *
     *     @SWG\Schema(
     *     required={"item_id"},
     *     type = "integer",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "item_code",
     *     description = "select package or explain",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"text_value"},
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


    public function payment(Request $request)
    {
        $data = $request->toArray();
        $user = User::find($data['user_id']);
        if ($user == null) {
            return response()->json($this->setArrayData(400, 'can not find user'), 400);
        }
        $profile = $user->profile()->get()->first();
        $current_coin = $profile->coin;
        if ($data['item_code'] === "package" || $data['item_code'] === "explain") {


            if ($data['item_code'] == 'explain') {
                $find = purchase::where('user_id',$data['user_id'])->where('item_code',$data['item_code'])->where('item_id',$data['item_id'])->get()->first();
                if($find != null)
                    return response()->json($this->setArrayData(400, 'item exists'), 400);
                $explain = Explain::find($data['item_id']);
                if ($explain == null) {
                    return response()->json($this->setArrayData(400, 'can not find explain item'), 400);
                }
                $coin_pay = $explain->explain_cost;
                if($current_coin >= $coin_pay){
                    $new_coin = $current_coin - $coin_pay;
                    $profile->update(['coin'=>$new_coin]);
                }
                else{
                    return response()->json($this->setArrayData(400, 'coin enought pay to explain'), 400);
                }
            }
            if ($data['item_code'] == 'package') {
                $find = purchase::where('user_id',$data['user_id'])->where('item_code',$data['item_code'])->where('item_id',$data['item_id'])->get()->first();
                if($find != null)
                    return response()->json($this->setArrayData(400, 'item exists'), 400);
                $package = Package::find($data['item_id']);
                if($package == null){
                    return response()->json($this->setArrayData(400, 'package not exits'), 400);
                }
                $coin_pay = $package->package_cost;
                if($current_coin >= $coin_pay){
                    $new_coin = $current_coin - $coin_pay;
                    $profile->update(['coin'=>$new_coin]);
                }
                else{
                    return response()->json($this->setArrayData(400, 'coin enought pay to package'), 400);
                }
            }


            return $this->addNewData($this->model, $data);
        } else
            return response()->json($this->setArrayData(400, 'item_code invalid'), 400);
    }

    /**
     * @SWG\Post(
     *     path="/purchase/delete",
     *     summary="delete purchase ",
     *     tags={"Purchase"},
     *     description="delete with purchase_id",
     *     operationId="purchasedelete",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "uid",
     *      description = "uid delete",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"uid"},
     *     type = "integer"
     *      )
     *           ),
     *     @SWG\Parameter(
     *      name = "purchase_id",
     *      description = "purchase_id",
     *     in ="formData",
     *     required = true,
     *     type="integer",
     *     @SWG\Schema(
     *     required={"purchase_id"},
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


    public function deletePurchase(Request $request)
    {
        $purchase = purchase::find($request->input('purchase_id'));
        if ($purchase == null) {
            return response()->json($this->setArrayData(400, 'can not find purchase'), 400);
        }

        $purchase->delete();
        return response()->json($this->setArrayData(200, 'delete success'), 200);

    }

}
