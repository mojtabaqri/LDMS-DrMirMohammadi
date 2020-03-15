<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::group(['middleware'=>['auth:api'],'namespace'=>'Api'],function (){
    Route::resource('user','UserController');
    Route::get('/verify','UserController@verify');
    Route::post('/user/verifyEmail','UserController@verifyEmail');
    Route::post('/user/delete','UserController@deleteAll')->name('deleteAll');
});
Route::post('login','Api\AuthController@login');
