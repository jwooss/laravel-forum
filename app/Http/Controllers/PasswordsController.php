<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getRemind()
    {
        return view('emails.passwords.remind');
    }

    public function postRemind(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|exists:users']);

        $email = $request->get('email');
        $token = str_random(64);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        Mail::send('emails.passwords.reset', compact('token'), function ($message) use ($email) {
            $message->to($email);
            $message->subject(sprintf('[%s] 비밀번호를 초기화하세요', config('app.name')));
        });

        flash('비밀번호를 바꾸는 방법은 담은 이메을 발송했습니다. 메일박스를 확인해주세요');

        return redirect('/');
    }

    public function getReset($token = null)
    {
        return view('passwords.reset', compact('token'));
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed',
            'token' => 'required',
        ]);

        $token = $request->get('token');

        if (!DB::table('password_resets')->whereToken($token)->first()) {
            flash('URL이 정확하지 않습니다.');

            return back()->withInput();
        }

        DB::table('password_resets')->whereToken($token)->delete();

        flash('비밀번호를 바꾸었습니다. 새로운 비밀번호를 로그인 하세요');

        return redirect('/');
    }
}
