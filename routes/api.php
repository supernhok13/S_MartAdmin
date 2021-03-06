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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group([ 'prefix' => 'api', 'namespace' => 'Api'],function () {
    Route::post('login', 'StaffApiController@login')->name("login");
    Route::post('logout', 'StaffApiController@logout')->name("logout");
    Route::get('check-login', 'StaffApiController@checkLogin')->name("check-login");
});


Route::group(['middleware' => ['web','auth:api'], 'prefix' => 'api/staff', 'namespace' => 'Api'],function () {
    Route::get('get-staff', 'StaffApiController@getStaff');


});


