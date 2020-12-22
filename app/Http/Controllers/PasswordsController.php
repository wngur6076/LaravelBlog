<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getRemind()
    {
        return view('passwords.remind');
    }

    public function postRemind(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|exists:users']);

        $email = $request->get('email');
        $token = str_random(64);

        \DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        event(new \App\Events\PasswordRemindCreated($email, $token));

        return $this->respondSuccess(
            trans('auth.passwords.sent_reminder')
        );
    }

    public function getReset($token = null)
    {
        return view('passwords.reset', compact('token'));
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);
        $token = $request->get('token');
        $user = \DB::table('password_resets')->whereToken($token)->first();
        
        if (! $user) {
            flash('URL이 정확하지 않습니다.');

            return back()->withInput();
        }

        \App\User::whereEmail($user->email)->first()->update([
            'password' => bcrypt($request->input('password'))
        ]);
        \DB::table('password_resets')->whereToken($token)->delete();

        flash('비밀번호를 바꾸었습니다. 새로운 비밀번호로 로그인 하세요.');

        return redirect('/');
    }
    
    protected function respondSuccess($message)
    {
        flash($message);

        return redirect('/');
    }
}
