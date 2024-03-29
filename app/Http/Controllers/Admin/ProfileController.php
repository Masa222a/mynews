<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//Profileモデルを扱えるようにする
use App\Profile;  
// ProfileHistoryモデルを扱えるようにする
use App\ProfileHistory;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $cond_name = $request->cond_name;
        if ($cond_name != '') {
          //検索されたら検索結果を取得する
          $posts = Profile::where('name', $cond_name)->get();
        } else {
          //それ以外はすべてのニュースを取得する
          $posts = Profile::all();
        }
        return view('admin.profile.index', ['posts' => $posts, 'cond_name' => $cond_name]);
    }
    
    
    public function add()
    {
        return view('admin.profile.create');
    }
    
    public function create(Request $request)
    {
        //Validationを行う
        $this->validate($request, Profile::$rules);
        
        $profile = new Profile;
        $form = $request->all();
        
        
        //フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        
        //データベースに保存
        $profile->fill($form);
        $profile->save();
        
        
        return redirect('admin/profile/create');
    }
    
    public function edit(Request $request)
    {
       //Profile Modelからデータを取得
       $profile = Profile::find($request->id);
        if (empty($profile)) {
          abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {            
        //Validationをかける
        $this->validate($request, Profile::$rules);
        
        //Profile Modelからデータを取得
        $profile = Profile::find($request->id);
        
        //送信されてきたフォームデータを格納する
        $profile_form = $request->all();
        unset($profile_form['_token']);
        
        //該当するデータを上書きして保存する
        $profile->fill($profile_form)->save();
        
        $profilehistory = new ProfileHistory;
        $profilehistory->profile_id = $profile->id;
        $profilehistory->change_log_at = Carbon::now();
        $profilehistory->save();
        
        return redirect('admin/profile/');
    }
    
    public function delete(Request $request)
    {
        //該当するNews Modelを取得
        $profile = Profile::find($request->id);
        
        //削除する
        $profile->delete();
        return redirect('admin/profile/');
    }
}
