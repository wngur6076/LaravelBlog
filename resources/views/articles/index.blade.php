@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.index'; @endphp

<div class="container">
    <div class="container">
        <header class="page-header">
            <h2>마크다운 뷰어</h2>

            <h4>
                <a href="{{ route('articles.index') }}">
                    포럼
                </a>
                <small>
                    / 글 목록
                </small>
            </h4>

            <div class="text-right action__article">
                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-circle"></i>
                    새 글 쓰기
                </a>
                <div class="btn-group sort__article">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-sort"></i> 목록 정렬 <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        @foreach (config('project.sorting') as $colum => $text)
                            <li {!! request()->input('sort') == $colum ? 'class="active"' : '' !!}>
                                {!! link_for_sort($colum, $text) !!}
                            </li>                 
                        @endforeach
                    </ul>
                </div>
            </div>
        </header>

        <div id="row">
            <div class="col-md-3">
                <aside>
                    @include('articles.partial.search')
                    @include('tags.partial.index')
                </aside>
            </div>

            <div class="col-md-9">
                <article>
                    @forelse($articles as $article)
                        @include('articles.partial.article', compact('article'))
                    @empty
                        <p class="text-center text-danger">
                            글이 없습니다.
                        </p>
                    @endforelse
                </article>
                @if($articles->count())
                    <div class="text-center paginator__article">
                        {!! $articles->appends(request()->except('page'))->render() !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 