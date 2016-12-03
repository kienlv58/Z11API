<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use App\User;
use JWTAuth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;

use App\Http\Requests;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'email' => 'required|email|max:255',
                'password' => 'required|min:6',
                //password_confirmation
            ],
            [
                'email.required' => 'Please enter your email',
                'email.email' => 'Email invalid',
                'email.max' => 'Email long',
                'password.required' => 'Please enter your password',
                'password.min' => 'Password so short',
            ]
        );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @SWG\Post(
     *     path="/auth/login",
     *     summary="login",
     *     tags={"0.Auth"},
     *     description="user login",
     *     operationId="login",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *      name = "grant_type",
     *     description = "grant_type of user: password,facebook,google",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"grant_type"},
     *     type = "string",
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "email",
     *     description = "email of user",
     *     in ="formData",
     *     required = false,
     *     type="string",
     *     @SWG\Schema(
     *     required={"email"},
     *     type = "string"
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "password",
     *     description = "password of user",
     *      required = false,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"password"},
     *     type = "string",
     *     format = "password"
     *      )
     *     ),
     *      @SWG\Parameter(
     *      name = "token",
     *     description = "token of social network",
     *     in ="formData",
     *     required = false,
     *     type="string",
     *     @SWG\Schema(
     *     required={"token"},
     *     type = "string"
     *      )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="login succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid email or password",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $grant_type = $request->input('grant_type');
        if ($grant_type === 'facebook' || $grant_type === 'google') {

        } else if ($grant_type === 'password') {
            $data = $request->toArray();
            if ($this->validator($data)->fails()) {
                return response()->json(
                    [
                        'code' => 400,
                        'status' => $this->validator($data)->errors(),
                        'user' => ''
                    ], 400
                );
            } else {
                $result = JWTAuth::attempt(['email' => $data['email'], 'password' => $data['password']]);
                //return ($result) ? 1:0;

                if ($result) {
                    $user = User::select('id', 'email', 'active')->where('email', $data['email'])->get()->first();
                    $user->profile = $user->profile()->get();
                   // if ($user->active == 0) {
//                        return response()->json(
//                            [
//                                'code' => 400,
//                                'status' => 'account not active',
//                            ], 400
//                        );
                   // }
                    $jwt = ['id' => $user->id, 'email' => $user->email, 'password' => $data['password']];
                    $token = JWTAuth::fromUser((object)$jwt);
                    return response()->json(
                        [
                            'code' => 200,
                            'status' => 'login succes',
                            'metadata' => [
                                'user' => $user->toArray(),
                                'token' => $token]
                        ], 200
                    );
                } else {
                    return response()->json(
                        [
                            'code' => 400,
                            'status' => 'username or password incorrect',
                        ], 400
                    );
                }

            }
        } else {
            return response()->json(
                [
                    'code' => 400,
                    'status' => 'grant_type invalid. please choose: password/facebook/google',
                ], 400
            );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
