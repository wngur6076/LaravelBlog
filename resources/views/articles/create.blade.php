@extends('layouts.app')

@section('content')
<div class="container">
    <h1>새포럼 글쓰기</h1>
    <hr />
    <form action="{{ route('articles.store') }}" method="POST">
        {!! csrf_field() !!}

        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title">제목</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control">
            {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
        </div>
        
        <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
            <label for="content">제목</label>
            <textarea name="content" id="content" rows="10" class="form-control">
                    {{ old('content') }}
                </textarea>
            {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
        </div>
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