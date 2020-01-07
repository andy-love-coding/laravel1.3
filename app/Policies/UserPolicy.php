<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // 自己才能更新自己
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    // 自己不能删除自己 且 必须是管理员才能删除
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id && $currentUser->is_admin;
    }

    // 是否有 关注 资格 （自己不能关注自己）
    public function follow(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id;
    }
}
