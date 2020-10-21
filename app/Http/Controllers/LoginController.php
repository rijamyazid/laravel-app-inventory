<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\User;

class LoginController extends Controller
{
    public function auth(Request $request){
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('user_username', '=', $request->username)
                ->where('user_password', '=', $request->password)
                ->first();
        
        if(!is_null($user)){
            Session::put('username', $request->username);
            Session::put('role', $user->bidang->bidang_name);
            Session::put('rolePrefix', $user->bidang->bidang_prefix);
            
            return redirect('/' . $user->bidang->bidang_prefix . '/dashboard');
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
