<a href="#">
  <strong class="stat" id="followings">
    {{ count($user->followings) }}
  </strong>
  关注
</a>
<a href="#">
  <strong class="stat" id="followers">
    {{ count($user->followers) }}
  </strong>
  粉丝
</a>
<a href="#">
  <strong class="stat" id="statuses">
    {{ $user->statuses()->count() }}
  </strong>
  微博
</a>