<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        // 需登录的操作
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        
        // 只有游客能做的操作
        $this->middleware('guest', [
            'only' => ['create', 'store']
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|confirmed|min:6'
        ], [
            'name.required' => '姓名都不写，想上天么？'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已经发送到您的邮箱，请注意查收！');
        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)    
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        
        session()->flash('success', '更新个人资料成功！');
        return redirect()->route('users.show', $user->id);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '删除用户成功！');
        return back();
    }

    // 发送激活邮件
    public function sendEmailConfirmationTO($user)
    {
        $view = 'emails.confirm'; // 邮件视图
        $data = compact('user'); // 传给邮件视图的数据
        // $from = '000@qq.com';
        // $name = '刘金';
        $to = $user->email;
        $subject = '感谢注册，请激活你您的邮箱账号。';
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            // $message->from($from, $name)->to($to)->subject($subject);
            $message->to($to)->subject($subject);
        });
    }

    // 确认邮箱（激活）
    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrfail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', $user->id);
    }
}
