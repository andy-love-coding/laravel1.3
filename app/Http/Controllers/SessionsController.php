<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // 只让未登录用户访问：登录页面
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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            session()->flash('success', '登录成功！');

            $fallback = route('users.show', Auth::user());
            return redirect()->intended($fallback); // 友好转向，登录成功跳转上一步尝试访问的页面，没有的话，默认跳转个人资料页面
        } else {
            session()->flash('danger', '很抱歉，您的账号和密码不匹配！');
            return redirect()->back()->withInput(); // withInput()返回时，能使 old() 保持表单前次的值
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect()->route('login');
    }
}
