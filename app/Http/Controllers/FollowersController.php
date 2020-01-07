<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 关注
    public function store(User $user)
    {
        $this->authorize('follow', $user);

        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }

        // 关注之后，跳转至 关注人 的主页
        return redirect()->route('users.show', $user->id);
    }

    // 取关
    public function destroy(User $user)
    {
        $this->authorize('follow', $user);

        if (Auth::user()->isFollowing($user->id)){
            Auth::user()->unfollow($user->id);
        }

        // 取关之后，跳转至 关注人 的主页
        return redirect()->route('users.show', $user->id);
    }
}
