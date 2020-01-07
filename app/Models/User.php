<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
        
        // 监听 creating()
        static::creating(function($user) {
            $user->activation_token = Str::random(10);
        });
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = 100)
    {
        $hash = md5(strtolower(trim($this->attribute['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at', 'desc');
    }

    // 本 model 为博主 （博主的粉丝类别）
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 本 model 为粉丝 （粉丝的博主列表）
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 关注
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }

        $this->followings()->sync($user_ids, false);
    }

    // 取关
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }

        $this->followings()->detach($user_ids);
    }

    // 是否关注
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
