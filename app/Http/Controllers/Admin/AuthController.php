<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(){
        return view('admin.auth.login');
    }
    public function login(Request $request){
        $credential = request()->only('email','password');
        $checkAuth = Auth::guard('admin')->attempt($credential);
        if(!$checkAuth){
            return redirect()->back()->with('Wrong Email and Password');
        }
        return redirect('/admin/')->with('success','Welcome '.auth()->guard('admin')->name);
    }
    public function logout(){
        auth()->guard('admin')->logout();
        return redirect('/');
    }
}
