<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index($slug = null)
    {
        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new \App\Article;

        $articles = $query->paginate(3);
        // dd(view('articles.index', compact('articles'))->render());

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $article = new \App\Article;

        return view('articles.create', compact('article'));
    }

    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        $article = $request->user()->articles()->create($request->all());
        if (! $article) {
            flash()->error(
                trans('글이 저장되지 않았습니다.')
            );

            return back()->withInput();
        }
        // event(new \App\Events\ArticleEvent($article));
        $article->tags()->sync($request->input('tags'));

        // 첨부파일 연결
        $request->getAttachments()->each(function ($attachment) use ($article) {
            $attachment->article()->associate($article);
            $attachment->save();
        });

        return $this->respondCreated($article);
    }

    public function show(\App\Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(\App\Article $article)
    {
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $article->update($request->all());
        $article->tags()->sync($request->input('tags'));
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('articles.show', $article->id));
    }
    
    public function destroy(\App\Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([], 204);
    }

    protected function respondCreated($article)
    {
        flash()->success(
            trans('작성하신 글이 저장되었습니다.')
        );

        return redirect(route('articles.show', $article->id));
    }
}