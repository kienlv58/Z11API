<?php

namespace App\Http\Controllers;

use App\Package;
use App\User;
use App\UserActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class MyLessionController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/lession",
     *     summary="my lession",
     *     tags={"Lession"},
     *     description="get my lession",
     *     operationId="package",
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

    public function getMyLession()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $mylession = UserActions::where('user_id', $user->id)->get()->first();
        if ($mylession == null or $mylession->mylession_item == null) {
            return response()->json($this->setArrayData(400, 'lession not exist'), 400);
        } else {
            $arr_less = explode('|', $mylession->mylession_item);
            $arr_package = [];
            foreach ($arr_less as $key => $value) {
                $package = Package::find($value);
                if ($package != null) {
                    $chapters = $package->chapter()->get();

                    foreach ($chapters as $chapter) {
                        $chapter->groupquestion = $chapter->groupquestion()->get();
                    }
                    $package->chapters = $chapters;
                    array_push($arr_package, $package);
                }
            }

            return response()->json($this->setArrayData(200, 'success', $arr_package), 200);
        }
    }

    /**
     * @SWG\Post(
     *     path="/lession",
     *     summary="my lession",
     *     tags={"Lession"},
     *     description="add my lession",
     *     operationId="package",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *     in ="formData",
     *     description = "package_id.",
     *     type = "integer",
     *    required = true
     *     ),
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
    public function addLesstion(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);
        $data = $request->toArray();

        $package_id = $data['package_id'];
        $myless = UserActions::where('user_id', $user->id)->get()->first();
        if ($myless == null) {
            return $this->addNewData('App\UserActions', ['user_id' => $user->id, 'rate_item' => null, 'mylession_item' => $package_id]);
        } else {
            if ($myless->mylession_item == null) {
                return $this->editData('App\UserActions', ['mylession_item' => $package_id], ['user_id' => $user->id]);
            } else {
                $arr_less = explode('|', $myless->mylession_item);
                foreach ($arr_less as $value) {
                    if ($value == $package_id)
                        return response()->json($this->setArrayData(400, 'lession exist'), 400);
                }
                $newLession = $myless->mylession_item . '|' . $package_id;
                return $this->editData('App\UserActions', ['mylession_item' => $newLession], ['user_id' => $user->id]);
            }
        }
    }

    /**
     * @SWG\Delete(
     *     path="/lession/{package_id}",
     *     summary="my lession",
     *     tags={"Lession"},
     *     description="add my lession",
     *     operationId="package",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "package_id",
     *     in ="path",
     *     description = "package_id.",
     *     type = "integer",
     *    required = true
     *     ),
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
    public function deleteLession($package_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($user->id);

        $myless = UserActions::where('user_id', $user->id)->get()->first();
        if ($myless == null or $myless->mylession_item == null) {
            return response()->json($this->setArrayData(400, 'lession not exist'), 400);
        } else {
            $arr_less = explode('|', $myless->mylession_item);

            foreach ($arr_less as $key => $value) {
                if ($value == $package_id) {
                    unset($arr_less[$key]);
                    break;
                }
            }

            if (count($arr_less) > 0) {
                $new_less = null;
                foreach ($arr_less as $key => $value) {
                    $new_less = $new_less . '|' . $arr_less[$key];
                }
                $new_less = substr($new_less, 1);
                return $this->editData('App\UserActions', ['mylession_item' => $new_less], ['user_id' => $user->id]);
            } else {
                return $this->editData('App\UserActions', ['mylession_item' => ''], ['user_id' => $user->id]);
            }

        }
    }
}
