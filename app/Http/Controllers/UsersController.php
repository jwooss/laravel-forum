<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $confirmCode = str_random(60);

        $user = \App\User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'confirm_code' => $confirmCode
        ]);

        event(new \App\Events\UserCreated($user));

        return $this->responseCreated('가입하신 메일 계정으로 인증 url을 전송하였습니다.');
    }

    public function confirm($code)
    {
        $user = \App\User::whereConfirmCode($code)->first();

        if (!$user) {
            flash('URL이 정확하지 않습니다.');

            return redirect('/');
        }

        $user->activated = 1;
        $user->confirm_code = null;
        $user->save();

        auth()->login($user);

        return $this->responseCreated(auth()->user()->name . '님, 환영합니다. 가입 확인되었습니다.', 'home');
    }

    protected function responseCreated($message, $path = '/')
    {
        flash($message);

        return redirect($path);
    }
}
