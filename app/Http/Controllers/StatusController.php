<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Status;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // store 和 destroy 都需要登录 
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        // Auth::user()->statuses()->create() 方法创建的微博，会自动与用户进行关联（即自动补充user_id）
        Auth::user()->statuses()->create([
            'content' => $request->content
        ]);

        session()->flash('success', '发布微博成功！');
        return redirect('/');
    }

    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();

        session()->flash('success', '微博已被成功删除！');
        return back();
    }
}
