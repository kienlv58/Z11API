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
        Route::get('get_all/{take?}/{skip?}', 'FolderController@getAllFolder');
        Route::post('add', 'FolderController@addFolder');
        Route::post('edit', 'FolderController@editFolder');
        Route::post('delete', 'FolderController@deleteFolder');
    });

    //package
    Route::group(['prefix' => 'package'], function () {
        Route::get('get/{id?}', 'PackageController@getPackage');
        Route::get('get_all/{take?}/{skip?}', 'PackageController@getAllPackage');
        Route::post('add', 'PackageController@addPackage');
        Route::post('edit', 'PackageController@editPackage');
        Route::post('delete', 'PackageController@deletePackage');
    });

    //Chapter
    Route::group(['prefix' => 'chapter'], function () {
        Route::get('get/{id?}', 'ChapterController@getChapter');
        Route::get('get_all/{take?}/{skip?}', 'ChapterController@getAllChapter');
        Route::post('add', 'ChapterController@addChapter');
        Route::post('edit', 'ChapterController@editChapter');
        Route::post('delete', 'ChapterController@deleteChapter');
    });

    //GroupQuestion
    Route::group(['prefix' => 'group_question'], function () {
        Route::get('get/{id?}', 'GroupQuestionController@getGroupQuestion');
        Route::get('get_all/{take?}/{skip?}', 'GroupQuestionController@getAllGroupQuestion');
        Route::post('add', 'GroupQuestionController@addGroupQuestion');
        Route::post('edit', 'GroupQuestionController@editGroupQuestion');
        Route::post('delete', 'GroupQuestionController@deleteGroupQuestion');
    });

    //Question
    Route::group(['prefix' => 'question'], function () {
        Route::get('get/{id?}', 'QuestionController@getQuestion');
        Route::get('get_all/{take?}/{skip?}', 'QuestionController@getAllQuestion');
        Route::post('add', 'QuestionController@addQuestion');
        Route::post('edit', 'QuestionController@editQuestion');
        Route::post('delete', 'QuestionController@deleteQuestion');
    });

    //Answer
    Route::group(['prefix' => 'answer'], function () {
        Route::get('get/{id?}', 'AnswerController@getAnswer');
        Route::get('get_all/{take?}/{skip?}', 'AnswerController@getAllAnswer');
        Route::post('add', 'AnswerController@addAnswer');
        Route::post('edit', 'AnswerController@editAnswer');
        Route::post('delete', 'AnswerController@deleteAnswer');
    });
    //purchase
    Route::group(['prefix' => 'purchase'], function () {
        Route::get('get_user/{id?}', 'PurchaseController@getUserPurchase');
        Route::get('getUserPurchase_package/{id?}', 'PurchaseController@getUserPurchase_package');
        Route::get('getUserPurchase_explain/{id?}', 'PurchaseController@getUserPurchase_explain');
        Route::get('getPurchaseId/{id?}', 'PurchaseController@getPurchaseId');
        Route::get('get_all/{take?}/{skip?}', 'PurchaseController@getAllPurchase');
        Route::post('add_payment', 'PurchaseController@payment');
       // Route::post('edit', 'PurchaseController@editAnswer');
        Route::post('delete', 'PurchaseController@deletePurchase');
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

