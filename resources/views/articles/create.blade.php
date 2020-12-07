@extends('layouts.app')

@section('content')
<div class="container">
    <h1>새포럼 글쓰기</h1>
    <hr />
    <form action="{{ route('articles.store') }}" method="POST">
        {!! csrf_field() !!}

        @include('articles.partial.form')
        <div class="form-group text-center">
            <div id="form-group">
                <button type="submit" class="btn btn-primary">
                    저장하기
                </button>
            </div>
        </div>
    </form>
</div>
@endsection