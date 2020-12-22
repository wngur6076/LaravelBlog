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
    Route::post('auth/login', [
        'as' => 'sessions.store',
        'uses' => 'SessionsController@store'
    ]);

    Route::post('auth/refresh', [
        'middleware' => 'jwt.refresh',
        'as' => 'sessions.refresh',
        function () {
        },
    ]);

    Route::group(['prefix' => 'v1', 'namespace' => 'v1', 'as' => 'v1.'], function () {
        /* 환영 메시지 */
        Route::get('/', [
            'as' => 'index',
            'uses' => 'WelcomeController@index'
        ]);
        
        /* 포럼 API */
        Route::resource('articles', 'ArticlesController');
        Route::get('tags/{slug}/articles', [
            'as' => 'tags.articles.index',
            'uses' => 'ArticlesController@index'
        ]);
        Route::get('tags', [
            'as' => 'tags.index',
            'uses' => 'ArticlesController@tags'
        ]);

        Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);
        Route::resource('articles.attachments', 'AttachmentsController', ['only' => ['index']]);
        Route::resource('comments', 'CommentsController', ['only' => ['show', 'update', 'destroy']]);
        Route::resource('articles.comments', 'CommentsController', ['only' => 'index', 'store']);
        Route::post('comments/{comment}/votes', [
            'as' => 'comments.vote',
            'uses' => 'CommentsController@vote'
        ]);
    });

    /**
     * 소셜 로그인
     *
     * 소셜로그인은 클라이언트 측에서한다.
     * 클라이언트에서 소셜사용자가 확인되면 서버에 소셜사용자 정보를 던진다.
     * 서버는 받은 사용자 객체로 로그인인다. 없으면 만든다.
     * 로그인하면 서버는 클라이언트에게 토큰을 발급한다.
     */
    Route::post('social/{provider}', [
        'as' => 'social.login',
        'uses' => 'SocialController@store',
    ]);

    /**
     * 비밀번호 초기화
     *
     * 클라이언트가 비밀번호 바꾸기 요청을 하면 서버는 비밀번호 바꾸는 방법을 담은 메일을 보낸다.
     * 사용자가 메일에서 링크를 클릭하면 웹브라우저가 작동하고, 그 이후 모든 과정은 웹에서 이루어 진다.
     * 바꾼 비밀번호는 서버에 저장되어 있고, 다음번 클라이언트에서 바꾼 비밀번호로
     * 로그인을 시도하면 유효한 토큰을 발급 받을 수 있다.
     */
    Route::post('auth/remind', [
        'as' => 'remind.store',
        'uses' => 'PasswordsController@postRemind',
    ]);
});