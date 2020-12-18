@extends('layouts.app')

@section('content')
<form action="{{ route('sessions.store') }}" method="POST" class="form__auth">
    {!! csrf_field() !!}

    @if ($return = request('return'))
        <input type="hidden" name="return" value="{{ $return }}">
    @endif

    <div class="page-header" style="text-align: center">
        <h4>로그인</h4>
        <p class="text-muted">
            구글 계정으로 로그인하세요. {{ config('app.name') }} 계정으로 로그인할 수도 있습니다.
        </p>
    </div>

    <div class="form-group">
        <a class="btn btn-default btn-lg btn-block"
            href="{{ route('social.login', ['google']) }}">
            <strong>
                <i class="fa fa-google"></i>
                google 계정으로 로그인하기
            </strong>
        </a>
    </div>

    <div
        class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        <input type="email" name="email" class="form-control"
            placeholder="{{ trans('auth.form.email') }}" value="{{ old('email') }}"
            autofocus />
        {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div
        class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
        <input type="password" name="password" class="form-control"
            placeholder="{{ trans('auth.form.password') }}">
        {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
    </div>

    <div id="form-group">
        <div id="checkbox">
            <label>
                <input type="checkbox" name="remember" value="{{ old('remember', 1) }}" checked>
                로그인 기억하기 <span class="text-danger">(공용 컴퓨터에서는 사용하지 마세요!)</span>
            </label>
        </div>
    </div>


    <div class="form-group">
        <button class="btn btn-primary btn-lg btn-block" type="submit">
            {{ trans('auth.sessions.title') }}
        </button>
    </div>

    <div>
        <p class="text-center">회원이 아니라면?
            <a href="{{ route('users.create') }}">가입하세요.</a>
        </p>
        <p class="text-center">
            <a href="{{ route('remind.create') }}">비밀번호를 잊으셨나요?</a>
        </p>
    </div>

</form>
@endsection