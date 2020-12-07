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

    public function edit($id)
    {
        return __METHOD__ . '은 다음 기본 키를 가진 아타클 모델을 수정하기 위한 폼을 담는 뷰를 반환합니다.' . $id;
    }

    public function update(Request $request, $id)
    {
        return __METHOD__ . '은 다음 사용자 폼 데이터로 다음 기본키를 가진 아티클 모델을 수정합니다.' . $id;
    }
    
    public function destroy($id)
    {
        return __METHOD__ . '삭제합니다' . $id;
    }
}
