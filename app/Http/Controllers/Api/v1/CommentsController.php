<?php

namespace App\Http\Controllers\Api\v1;

use App\Article;
use App\Http\Controllers\CommentsController as ParentController;

class CommentsController extends ParentController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware = [];
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function index(Article $article)
    {
        return json()->withCollection(
            $article->comments,
            new \App\Transformers\CommentTransformer
        );
    }
}