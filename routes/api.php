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
    Route::get('/verify','UserController@verify');
    Route::resource('user','UserController');
    Route::post('/user/verifyEmail','UserController@verifyEmail');
    Route::post('/user/delete','UserController@deleteAll');
    Route::post('/user/updatePhoto','UserController@updatePhoto');
    Route::post('/user/getProfile','UserController@getProfile');
    Route::post('/logout','UserController@logout');
    //آغار روت های مربوط به مطالبات کاربران
    Route::resource('demand','DemandController')->only(['store','index','destroy','update','show']);
    Route::post('/demand/delete','DemandController@deleteAll')->name('deleteAllDemand');
    Route::get('/demand/tracking/{id}','DemandController@trackingDemand')->name('trackingDemand');

});
Route::post('login','Api\UserController@login');
