<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Admin;

class LoginController extends Controller
{
    public function auth(Request $request){
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('admin_username', '=', $request->username)
                ->where('admin_password', '=', $request->password)
                ->first();
        
        if(!is_null($admin)){
            Session::put('username', $request->username);
            Session::put('role', $admin->bidang->bidang_name);
            Session::put('rolePrefix', $admin->bidang->bidang_prefix);
            
            return redirect('/' . $admin->bidang->bidang_prefix . '/dashboard');
        } else {
            throw ValidationException::withMessages(['username' => 'Username atau password salah']);
        }
    }

    public function guest(){
        Session::put('username', "Guest");
        Session::put('role', "Guest");
        Session::put('rolePrefix', "guest");
        Session::save();

        return redirect('/guest/dashboard');
    }
}
