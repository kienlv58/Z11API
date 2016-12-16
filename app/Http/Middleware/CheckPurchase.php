<?php

namespace App\Http\Middleware;

use App\Category;
use App\Explain;
use App\GroupQuestion;
use App\purchase;
use App\Question;
use App\User;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CheckPurchase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        $user = User::findOrFail($user->id);

        $data = $request->route()->parameters();


        if (array_key_exists('group_question_id', $data) == true) {
            $group = GroupQuestion::find($data['group_question_id']);
            if ($group == null) {
                return response()->json(['code' => 404, 'status' => 'cant not find groupquestion'], 404);
            }

            if ($group->explain_item_id == 0) {
                return $next($request);
            } else {

                $chapter = $group->chapter()->get()->first();
                $package = $chapter->package()->get()->first();
                $user_charge_pkg = purchase::where('item_code', 'package')->where('item_id', $package->package_id)->where('user_id', $user->id)->get()->first();
                if ($user_charge_pkg == null) {
                    $explain = Explain::find($group->explain_item_id);
                    $user_charge_explain = \App\purchase::where('item_code', 'explain')->where('item_id', $explain->explain_item_id)->where('user_id', $user->id)->get()->first();
                    if($user_charge_explain == null){
                        return response()->json(['code' => 400, 'status' => 'you must pay for question'], 400);
                    }
                    else{
                        return $next($request);
                    }
                } else {
                    return $next($request);
                }
            }
        }
        elseif(array_key_exists('question_id', $data) == true){

            $question = Question::find($data['question_id']);
            if ($question == null) {
                return response()->json(['code' => 404, 'status' => 'cant not find question'], 404);
            }

            if ($question->explain_item_id == 0) {
                return $next($request);
            } else {
                $group = $question->groupquestion()->get()->first();
                $chapter = $group->chapter()->get()->first();
                $package = $chapter->package()->get()->first();
                $user_charge_pkg = purchase::where('item_code', 'package')->where('item_id', $package->package_id)->where('user_id', $user->id)->get()->first();
                if ($user_charge_pkg == null) {
                    $explain = Explain::find($question->explain_item_id);
                    $user_charge_explain = \App\purchase::where('item_code', 'explain')->where('item_id', $explain->explain_item_id)->where('user_id', $user->id)->get()->first();
                    if($user_charge_explain == null){
                        return response()->json(['code' => 400, 'status' => 'you must pay for question'], 400);
                    }
                    else{
                        return $next($request);
                    }
                } else {
                    return $next($request);
                }
            }
        }

    }
}
