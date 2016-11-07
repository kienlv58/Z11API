<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use App\User;
use JWTAuth;
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
                'email.unique' => 'Email has exits',
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
    public function store(Request $request)
    {
        $data = (array)$request->all();
        if ($this->validator($data)->fails()) {
            return response()->json(
                [
                    'code' => 400,
                    'status' => $this->validator($data)->errors(),
                    'user' => ''
                ]
            );
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $id = $user->id;
            $profile = Profile::create([
                'user_id' => $id,
            ]);

            return response()->json(
                [
                    'code' => 200,
                    'status' => 'register succes',
                    'user' => ['name' => $data['name'], 'email' => $data['email']],
                ]
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
