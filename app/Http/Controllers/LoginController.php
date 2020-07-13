<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            
            $adminRole = $admin->role_id;

            switch ($adminRole) {
                case 1:
                    return redirect('/bidang-sekretariat');
                    break;
                case 2:
                    return redirect('/bidang-adpin');
                    break;
                case 3:
                    return redirect('/bidang-kbkr');
                    break;
                case 4:
                    return redirect('/bidang-kspk');
                    break;
                case 5:
                    return redirect('/bidang-dalduk');
                    break;
                case 6:
                    return redirect('/bidang-latbang');
                    break;                                                 
                default:
                    return redirect('/');
                    break;
            }

        } else {
            throw ValidationException::withMessages(['username' => 'Username atau password salah']);
        }
    }
}
