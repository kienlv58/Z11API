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
//login + register
    Route::post('users/register', 'RegisterController@store');
    Route::post('users/login', 'LoginController@store');
    Route::get('db', 'RestfulController@generateDB');


    //Authorization: Bearer {yourtokenhere}
    // 'middleware' => 'jwt.auth'
    Route::group(['middleware' => 'checkuser'], function () {

        //user
        Route::post('profile/edit', 'RestfulController@editProfile');
        Route::get('users/profile', 'RestfulController@getProfile');
        Route::put('users/chargecoin', 'RestfulController@chargeCoin');
        Route::put('users/profile', 'RestfulController@editProfile');
        Route::get('language', 'RestfulController@getLanguage');
        Route::post('language', 'RestfulController@addLanguage');
        Route::get('test', 'RestfulController@test');


        Route::get('users/{id}', 'AdminController@getUser');
        Route::get('user_mod', 'AdminController@getuser_Mod');
        Route::get('users/{limit?}/{offset?}', 'AdminController@getAllUser');
        Route::delete('users/{uid}', 'AdminController@deleteUser');
        Route::put('aprroval_package', 'AdminController@aprrovalPackage');
        Route::post('user_mod', 'AdminController@createUserMod');

        //categories
        Route::get('category/{category_id}', 'CategoryController@getCategories');
        Route::get('categories/{limit?}/{offset?}', 'CategoryController@getAllCategory');

        Route::get('searchCategories/{name?}/{category_code?}/{describe?}', 'CategoryController@searchCategories');
        Route::post('categories', 'CategoryController@addCategory');
        Route::put('categories', 'CategoryController@editCategory');
        Route::delete('categories/{category_code}', 'CategoryController@deleteCategory');

        //folder

        Route::get('folder/{id}', 'FolderController@getFolder');
        Route::get('folder_myfolder', 'FolderController@getMyFolder');
        Route::get('folders/{limit?}/{offset?}', 'FolderController@getAllFolder');
        Route::post('folders', 'FolderController@addFolder');
        Route::put('folders', 'FolderController@editFolder');
        Route::delete('folders/{folder_id}', 'FolderController@deleteFolder');


        //package
        Route::get('package/{package_id}', 'PackageController@getPackage');
        Route::post('packages', 'PackageController@addPackage');
        Route::put('packages', 'PackageController@editPackage');
        Route::put('package_rate', 'PackageController@updateRate');
        Route::put('package_edit_rate   ', 'PackageController@editRate');
        Route::delete('packages/{package_id}', 'PackageController@deletePackage');
        Route::get('packages/get_aprroval/{take}/{skip}','PackageController@getPackageAprroval');
        Route::get('packages/get_not_yet_aprroval/{take}/{skip}','PackageController@getPackageNotYetAprroval');
        Route::get('packages/get_not_aprroval/{take}/{skip}','PackageController@getPackageNotAprroval');
        Route::get('packages/{limit?}/{offset?}', 'PackageController@getAllPackage');

        //Chapter
        Route::get('chapter/{chapter_id}', 'ChapterController@getChapter');
        Route::get('chapters/{limit?}/{offset?}', 'ChapterController@getAllChapter');
        Route::post('chapters', 'ChapterController@addChapter');
        Route::put('chapters', 'ChapterController@editChapter');
        Route::delete('chapters/{chapter_id}', 'ChapterController@deleteChapter');

        //GroupQuestion
        Route::get('group_questions/{limit?}/{offset?}', 'GroupQuestionController@getAllGroupQuestion');
        Route::post('group_questions', 'GroupQuestionController@addGroupQuestion');
        Route::put('group_questions', 'GroupQuestionController@editGroupQuestion');
        Route::delete('group_questions/{id}', 'GroupQuestionController@deleteGroupQuestion');

        //Question
        Route::get('questions/{limit?}/{offset?}', 'QuestionController@getAllQuestion');
        Route::post('questions', 'QuestionController@addQuestion');
        Route::put('questions', 'QuestionController@editQuestion');
        Route::delete('questions/{question_id}', 'QuestionController@deleteQuestion');

        //Answer
        Route::get('answer/{id}', 'AnswerController@getAnswer');
        Route::get('answers/{limit?}/{offset?}', 'AnswerController@getAllAnswer');
        Route::post('answers', 'AnswerController@addAnswer');
        Route::put('answers', 'AnswerController@editAnswer');
        Route::delete('answers/{answer_item_id}', 'AnswerController@deleteAnswer');
        //purchase
        Route::get('purchases/users/{user_id}', 'PurchaseController@getUserPurchase');
        Route::get('purchases/package/{user_id}', 'PurchaseController@getUserPurchase_package');
        Route::get('purchases/explain/{user_id}', 'PurchaseController@getUserPurchase_explain');
        Route::get('purchases/status/{item_code}/{item_id}', 'PurchaseController@checkPurchase');
        Route::get('purchases', 'PurchaseController@getMyPurchase');
        Route::post('purchases', 'PurchaseController@payment');
        Route::delete('purchases/{$purchase_id}', 'PurchaseController@deletePurchase');
        Route::get('purchases/{id}', 'PurchaseController@getPurchaseId');
        Route::get('purchases/{limit?}/{offset?}', 'PurchaseController@getAllPurchase');


        //role
        Route::get('role/{name_role}','RoleController@getRole');
        Route::get('roles/{limit?}/{offset?}', 'RoleController@getAllRoles');
        Route::post('roles','RoleController@addRole');
        Route::put('roles','RoleController@editRole');
        Route::delete('roles/{name_role}','RoleController@deleteRole');

        ////user_role
        Route::get('user_roles/{user_id}','UserRoleController@getUserRole');
        Route::get('user_roles/{limit?}/{offset?}', 'UserRoleController@getAllUserRoles');
        Route::post('user_roles','UserRoleController@addUserRole');
        Route::put('user_roles','UserRoleController@editUserRole');
        Route::delete('user_roles/{user_id}/{name_role}','UserRoleController@deleteUserRole');

        //lession
        Route::get('lession','MyLessionController@getMyLession');
        Route::post('lession','MyLessionController@addLesstion');
        Route::delete('lession/{package_id}','MyLessionController@deleteLession');


        //useranswer
        Route::post('useranswer','UserAnswerController@addUserAnswer');


        Route::group(['middleware' => 'checkpurchase'], function () {

            //GroupQuestion
            Route::get('group_questions/{group_question_id}', 'GroupQuestionController@getGroupQuestion');


            //Question
            Route::get('questions/{question_id}', 'QuestionController@getQuestion');

        });
    });

});

