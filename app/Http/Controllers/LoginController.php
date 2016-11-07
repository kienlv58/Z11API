<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Validator;
use App\User;
use JWTAuth;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->toArray();
        if($this->validator($data)->fails()){
            return response()->json(
                [
                    'code' => 400,
                    'status' => $this->validator($data)->errors(),
                    'user' => ''
                ]
            );
        }
        else{
            $result = JWTAuth::attempt(['email'=>$data['email'],'password'=>$data['password']]);
            //return ($result) ? 1:0;

            if($result){
                $user = User::select('id', 'email', 'name')->where('email', $data['email'])->get()->first();
                $jwt = ['id'=>$user->id,'email'=>$user->email,'password'=>$data['password']];
                $token = JWTAuth::fromUser((object)$jwt);
                return response()->json(
                    [
                        'code'=>200,
                        'status'=>'login succes',
                        'user' => $user->toArray(),
                        'token' => $token
                    ]
                );
            }else{
                return response()->json(
                    [
                        'code'=>400,
                        'status'=>'username or password incorrect',
                        'user' => ''
                    ]
                );
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
