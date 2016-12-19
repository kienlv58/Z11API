<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\User;
use JWTAuth;
use \Queue;
use Illuminate\Http\Response;

use App\Http\Requests;

class RegisterController extends Controller
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
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
                //password_confirmation
            ],
            [
                'name.required' => 'Please enter your name',
                'name.max' => 'Name long',
                'email.required' => 'Please enter your email',
                'email.email' => 'Email invalid',
                'email.max' => 'Email long',
                'email.unique' => 'Email has exits. Check email if you not active',
                'password.required' => 'Please enter your password',
                'password.min' => 'Password so short',
                'password.confirmed' => 'Password confirm invalid',
            ]
        );
    }

    public function index()
    {
        //
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
     *     path="/users/register",
     *     summary="register",
     *     tags={"1.User"},
     *     description="user register",
     *     operationId="register",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
            name = "name",
     *      description = "name of user",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"name"},
     *     type = "string"
     *      )
*           ),
     *     @SWG\Parameter(
     *      name = "email",
     *     description = "email of user",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"email"},
     *     type = "string"
     *      )
     *     ),
     *      @SWG\Parameter(
     *      name = "gender",
     *     description = "gender of user",
     *     in ="formData",
     *     required = true,
     *     type="string",
     *     @SWG\Schema(
     *     required={"email"},
     *     type = "string"
     *      )
     *     ),
     *      @SWG\Parameter(
     *      name = "password",
     *     description = "password of user",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *
     *     @SWG\Schema(
     *     required={"password"},
     *     type = "string",
     *     format = "password"
     *      )
     *     ),
     *     @SWG\Parameter(
     *      name = "password_confirmation",
     *     description = "password_confirmation of user",
     *      required = true,
     *      in ="formData",
     *     type = "string",
     *     @SWG\Schema(
     *     required={"password_confirmation"},
     *     type = "string"
     *      )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="register succes",
     *
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Value",
     *     )
     * )
     */

    public function store(Request $request)
    {
        $data = $request->toArray();
        if ($this->validator($data)->fails()) {
            return response()->json(
                [
                    'code' => 400,
                    'status' => $this->validator($data)->errors(),
                ],400
            );
        } else {
            $user = new User();
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->grant_type = 'password';
            $user->token_social = null;
            $user->active = 0;
            $user->save();
            $id = $user->id;
            $profile = Profile::create([
                'user_id' => $id,
                'coin'=>200,
                'name'=>$data['name'],
                'gender'=>$data['gender']
            ]);
//                Mail::send('email.verify', ['name' => $data['name'], 'email' => $data['email'], 'link' => url('/user/activation/' . JWTAuth::attempt(['email' => $data['email'], 'password' => $data['password']]) . '/' . $user->id)], function ($message) use ($data) {
//                    $message->from('zone11@api.com', $name = 'zone11');
//                    $message->to($data['email'], $name = null);
//                    $message->subject('Verify Account');
//                });


            return response()->json(
                [
                    'code' => 200,
                    'status' => 'register succes. check email to Verify Account',
                    'metadata'=>[
                    'user' => ['name' => $data['name'], 'email' => $data['email']]],
                ],200
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
