<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    use \Illuminate\Foundation\Auth\ThrottlesLogins;

    protected $lockoutTime = 60;

    protected $maxLoginAttempts = 5;

    public function username()
    {
        return 'email';
    }

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // ThrottlesLogins 트레이트를 사용하면 사용자의 로그인 아이디와 IP 주소를 조합하여
        // 로그인 횟수 제한 기능을 활성화할 수 있다.
        $throttles = method_exists($this, 'hasTooManyLoginAttempts');

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $token = is_api_domain()
            ? jwt()->attempt($request->only('email', 'password'))
            : auth()->attempt($request->only('email', 'password'),
            $request->has('remember'));

        if (! $token) {
            // if (\App\User::socialUser($request->input('email'))->first()) {
            //     return $this->respondSocialUser();
            // }

            if ($throttles && ! $lockedOut) {
                // 로그인에 성공하지 못하면 로그인 실패 횟수가 증가시킨다.
                // $maxLoginAttempts로 정한 횟수를 초과해서 실패하면
                // $lockoutTime(초) 동안 로그인을 할 수 없다.
                $this->incrementLoginAttempts($request);
            }

            return $this->respondError('이메일 또는 비밀번호가 맞지 않습니다.');
        }

        if (! auth()->user()->activated) {
            auth()->logout();
            
            return $this->respondError('가입 확인해 주세요.');
        }

        flash(auth()->user()->name . '님, 환영합니다.');

        return $this->respondCreated($token);
    }

    protected function respondCreated($token)
    {
        flash(auth()->user()->name . '님, 환영합니다.');

        return ($return = request('return'))
            ? redirect(urldecode($return)) : redirect()->intended('home');
    }

    public function destroy()
    {
        auth()->logout();
        flash('또 방문해 주세요.');

        return redirect('/');
    }

    protected function respondError($message)
    {
        flash()->error($message);

        return back()->withInput();
    }

    protected function respondSocialUser()
    {
        flash()->error(
            trans('auth.sessions.error_social_user')
        );

        return back()->withInput();
    }
}
