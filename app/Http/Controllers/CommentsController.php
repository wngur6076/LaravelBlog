<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(\App\Http\Requests\CommentsRequest $request, 
    \App\Article $article)
    {
        $comment = $article->comments()->create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));
        
        flash()->success('작성하신 댓글을 저장했습니다.');

        return redirect(route('articles.show', $article->id) . '#comment_' . $comment->id);
    }

    public function update(\App\Http\Requests\CommentsRequest $request,
    \App\Comment $comment)
    {
        $comment->update($request->all());

        return redirect(route('articles.show', $comment->commentable->id) . '#comment_' . $comment->id);
    }

    public function destroy(\App\Comment $comment)
    {
        $comment->delete();

        return redirect()->json([], 204);
    }
}