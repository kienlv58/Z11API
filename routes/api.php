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


Route::group(['prefix' => 'v1'], function () {

    //Authorization: Bearer {yourtokenhere}
    Route::group(['prefix' => 'restricted', 'middleware' => 'jwt.auth'], function () {
        Route::get('profile/{id?}', 'RestfulController@getProfile');
    });

    Route::group(['prefix' => 'auth'], function () {

        Route::post('register', 'RegisterController@store');
        Route::post('login', 'LoginController@store');

    });
//none auth
    Route::group(['prefix' => 'category'], function () {
        Route::get('get/{id?}', 'CategoryController@getCategory');
        Route::get('get_all/{take?}/{skip?}', 'CategoryController@getAllCategory');

    });
//folder
    Route::group(['prefix' => 'folder'], function () {
        Route::get('get/{id?}', 'FolderController@getFolder');
        Route::get('get_all/{take?}/{skip?}', 'CategoryController@getAllFolder');
        Route::post('add', 'CategoryController@addFolder');
        Route::post('edit', 'CategoryController@editFolder');
        Route::post('delete', 'CategoryController@deleteFolder');
    });


//admin + mode
    Route::group(['prefix' => 'admin'], function () {

        Route::post('add_category', 'CategoryController@addCategory');
        Route::post('edit_category', 'CategoryController@editCategory');
        Route::post('delete_category', 'CategoryController@deleteCategory');

    });


});
//test
Route::post('test', 'CategoryController@test');

