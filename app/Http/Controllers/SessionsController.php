<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    public function __construct(){
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function create(){

    	return view('sessions.create');
    }

    public function store(Request $request){
    	$credentials = $this->validate($request,[
    		'email' => 'required|email|max:255',
    		'password' => 'required'
    	]);

    	if(Auth::attempt($credentials,$request->has('remember'))){
            if(Auth::user()->activated){
                session()->flash('success','欢迎回来！');
                return redirect()->intended(route('users.show',[Auth::user()]));
            }else{
                Auth::logout();
                session()->flash('waring','账号未激活，请查看邮箱是否有激活邮件。');
                return redirect('/');
            }
    	}else{
    		session()->flash('danger','很抱歉，您的邮箱和密码不匹配!');
    		return redirect()->back();
    	}
    }

    public function destroy(){
    	Auth::logout();
    	session()->flash('success','您已经成功退出！');
    	return redirect('login');
    }
}
