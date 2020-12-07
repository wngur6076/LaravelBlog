<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = \App\Article::latest()->paginate(3);
        $articles->load('user');
        // dd(view('articles.index', compact('articles'))->render());

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        $article = \App\User::find(1)->articles()->create($request->all());
        if (! $article) {
            return back()->with('flash_message', '글이 저장되지 않았습니다.')
                ->withInput();
        }
        // event(new \App\Events\ArticleEvent($article));
        
        return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장되었습니다.');
    }

    public function show(\App\Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(\App\Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $article->update($request->all());
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('articles.show', $article->id));
    }
    
    public function destroy(\App\Article $article)
    {
        $article->delete();

        return response()->json([], 204);
    }
}
