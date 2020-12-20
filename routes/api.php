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

Route::group(['domain' => config('project.api_domain'), 'namespace' => 'Api', 'as'
=> 'api.'], function () {
    // 인증 관련 라우트
    Route::group(['prefix' => 'v1', 'namespace' => 'v1', 'as' => 'v1.'], function () {
        // 리소스 관련 라우트
    });
});