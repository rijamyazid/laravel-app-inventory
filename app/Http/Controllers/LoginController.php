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

        $admin = Admin::whereUsernameAndPassword($request->username, $request->password)->first();
        
        if(!is_null($admin)){
            Session::put('user', $admin->username);
            
            var_dump($admin->username);

            $username = Session::get('user');
            echo $username;
            // return redirect('/'.$admin->role->role_prefix);
        } else {
            throw ValidationException::withMessages(['username' => 'Username atau password salah']);
        }
    }
}
