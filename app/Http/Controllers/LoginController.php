<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Admin;

class LoginController extends Controller
{
    public function auth(Request $request){

        if($request->has('username')){
            $this->validate($request,[
                'username' => 'required',
                'password' => 'required'
            ]);
    
            $admin = Admin::whereUsernameAndPassword($request->username, $request->password)->first();
            
            if(!is_null($admin)){
                Session::put('username', $request->username);
                Session::put('role', $admin->role->role);
                Session::put('rolePrefix', $admin->role->role_prefix);
                
                return redirect('/'.$admin->role->role_prefix);
            } else {
                throw ValidationException::withMessages(['username' => 'Username atau password salah']);
            }
        } else {
            Session::put('username', "Guest");
            Session::put('role', "Guest");
            Session::put('rolePrefix', "guest");
            Session::save();

            return redirect('/' . 'guest');
        }

    }
}
