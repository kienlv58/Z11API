<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//Authorization: Bearer {yourtokenhere}
Route::group(['prefix'=>'v1/restricted','middleware' => 'jwt.auth'],function () {
//        if (!$user = JWTAuth::parseToken()->authenticate()) {
//            return response()->json(['user not found'], 404);
//        } else {
            Route::post('profile/{id?}', 'RestfulController@getProfile');
       // }
    });

Route::group(['prefix' => 'v1/auth'], function () {

    Route::post('register', 'RegisterController@store');
    Route::post('login', 'LoginController@store');

});
Route::group(['prefix'=>'test'],function (){
    return 'test';
});

