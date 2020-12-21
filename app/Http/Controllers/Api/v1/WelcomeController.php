<?php

namespace App\Http\Controllers\Api\v1;

use \App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'name'      => config('app.name') . ' API',
            'message'   => 'This is a base endpoint of v1 API.',
            'link'      => [
                [
                    'rel'   => 'self',
                    'href'  => route(\Route::currentRouteName())
                ],
                [
                    'rel'   => 'api.v1.articles',
                    'href'  => route('api.v1.articles.index')
                ],
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    }
}