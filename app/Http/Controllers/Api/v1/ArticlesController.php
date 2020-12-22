<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Article;

class ArticlesController extends ParentController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware = [];
        // $this->middleware('auth.basic.once', ['except' => ['index', 'show', 'tags']]);
        $this->middleware('jwt.auth', ['except' => ['index', 'show', 'tags']]);
    }

    protected function respondCollection(LengthAwarePaginator $artlcles)
    {
        // return $article->toJson(JSON_PRETTY_PRINT);
        return json()->withPagination(
            $artlcles,
            new \App\Transformers\ArticleTransformer
        );
    }

    protected function respondInstance(Article $article, Collection $comments)
    {
        return json()->withItem(
            $article,
            new \App\Transformers\ArticleTransformer
        );
    }

    protected function respondCreated($article)
    {
        /* return response()->json(
            ['success' => 'created'],
            201,
            ['Location' => '생성한_리소스의_상세보기_API_앤드포인트'],
            JSON_PRETTY_PRINT
        ); */
        return json()->setHeaders([
            'Location' => route('api.v1.articles.show', $article->id),
        ])->created('created');
    }

    protected function respondUpdated(Article $article)
    {
        return json()->success('updated');
    }

    public function tags()
    {
        return json()->withCollection(
            \App\Tag::all(),
            new \App\Transformers\TagTransformer
        );
    }
}