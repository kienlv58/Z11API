<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('user/activation/{token}/{id}',function ($token,$id){
     DB::table('users')->where('id',$id)->update(['active'=>1]);
    return view('email.active');
});
Route::get('/test',function (){
    return 'test';
});