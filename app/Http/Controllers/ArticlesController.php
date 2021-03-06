<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticlesController extends Controller implements Cacheable
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function cacheTags()
    {
        return 'articles';
    }

    public function index(Request $request, $slug = null)
    {
        $cacheKey = cache_key($request->url());

        $query = $slug
            ? \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new \App\Article;

        $query = $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        if ($keyword = $request->input('q')) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

        $articles = $this->cache($cacheKey, 5, $query, 'paginate', 3);
        // $articles = $query->paginate(3);
        // dd(view('articles.index', compact('articles'))->render());

        return $this->respondCollection($articles, $cacheKey);
    }

    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey = null)
    {
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $article = new \App\Article;

        return view('articles.create', compact('article'));
    }

    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);
            
        $article = $request->user()->articles()->create($payload);
        // $article = \App\User::find(1)->articles()->create($payload);
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

        event(new \App\Events\ModelChanged(['articles']));

        return $this->respondCreated($article);
    }

    public function show(\App\Article $article)
    {
        if (! is_api_domain()) {
            $article->view_count += 1;
            $article->save();
        }

        $comments = $article->comments()->with('replies')->withTrashed()
            ->whereNull('parent_id')->latest()->get();

        return $this->respondInstance($article, $comments);
    }

    public function edit(\App\Article $article)
    {
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article->update($payload);
        $article->tags()->sync($request->input('tags'));

        return $this->respondUpdated($article);
    }
    
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return response()->json([], 204);
    }

    protected function respondCreated(Article $article)
    {
        flash()->success(
            trans('작성하신 글이 저장되었습니다.')
        );

        return redirect(route('articles.show', $article->id));
    }

    protected function respondUpdated(\App\Article $article)
    {
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('articles.show', $article->id));
    }

    protected function respondInstance(Article $article, Collection $comments)
    {
        return view('articles.show', compact('article', 'comments'));
    }
}