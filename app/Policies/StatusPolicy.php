<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    public function destroy($user, $status)
    {
        // 只有自己能删除自己的微博
        return $user->id === $status->user_id;
    }
}
