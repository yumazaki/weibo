<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{

    public function __construct()
    {

        $this->middleware('guest', [
            'only' => ['create']
        ]);

    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:225',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来！');
                return redirect()->intended(route('users.show', [Auth::user()]));
            } else {
                session()->flash('danger', '您的账户还未激活，请登录邮箱'. Auth::user()->email .'查看邮件并激活邮箱！');
                Auth::logout();
                return redirect('/');
            }

        } else {
            session()->flash('danger', '抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
