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

        \Mail::send('emails.passwords.reset', compact('token'), function ($message) use ($email) {
            $message->to($email);
            $message->subject(
                sprintf('[%s] 비밀번호를 초기화하세요.', config('app.name'))
            );
        });

        flash('비밀번호를 바꾸는 방법을 담은 이메일을 발송했습니다. 메일박스를 확인해 주세요.');
        
        return redirect('/');
    }
}
