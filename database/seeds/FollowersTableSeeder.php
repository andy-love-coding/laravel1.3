<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $user = $users->first();
        $user_id = $user->id;

        $followers = $users->slice($user_id);
        $follower_ids = $followers->pluck('id')->toArray();

        // 1号用户 关注 其他
        $user->follow($follower_ids);

        // 其他用户 关注 1号
        foreach($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
