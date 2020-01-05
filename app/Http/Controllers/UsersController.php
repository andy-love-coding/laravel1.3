<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
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
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6'
        ], [
            'name.required' => '姓名都不写，想上天么？'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success', '欢迎，您将开启一段新的旅程！');
        return redirect()->route('users.show', [$user]); // route() 方法会自动获取 model 的主键，[$user]同$user->id
    }
}
