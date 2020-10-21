<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Folder;
use App\File;
use App\Bidang;
use App\User;

class UsersController extends Controller
{

    public function index($bidangPrefix){
        if(is_null(Session::get('username'))) return redirect('/');

        Session::put('side_loc', 'dashboard');

        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();

        return view('content.index', 
                    [ 
                        'roleS'         => Session::get('role'), 
                        'bidangPrefix'  => $bidangPrefix,
                        'bidangS'       => $bidangS
                    ]);
    }

    public function view(){
        if(is_null(Session::get('username'))) return redirect('/');

        Session::put('side_loc', 'kelola_user');

        $users = User::get();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();      

        return view('content.users.view', 
            [
                'users'          => $users,
                'bidangPrefix'  => 'super_admin',
                'bidangS'       => $bidangS
            ]);
    }

    public function create(){
        if(is_null(Session::get('username'))) return redirect('/');

        $bidangs = Bidang::orderBy('bidang_name', 'asc')->get();

        return view('content.users.create', 
            [
                'bidangPrefix' => 'super_admin',
                'bidangS' => $bidangs
            ]);
    }

    public function store(Request $request){
        if(is_null(Session::get('username'))) return redirect('/');

        $this->validate($request,[
            'username' => 'required',
            'password' => 'required',
            'name' => 'required',
            'role' => 'required'
        ]);

        $username = $request->username;
        $password = $request->password;
        $name = $request->name;
        $role = $request->role;

        User::create([
            'user_username' => $username,
            'user_password' => $password,
            'user_name' => $name,
            'bidang_id' => $role,
        ]);

        return redirect('/'. Session::get('rolePrefix') .'/view/user');
    }

    public function edit($bidangPrefix, $username){
        if(is_null(Session::get('username'))) return redirect('/');

        $user = User::where('user_username' , '=' , $username)->first();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
        
        return view('content.users.edit', 
            [   
                'user' => $user,
                'bidangPrefix' => 'super_admin',
                'bidangS' => $bidangS
            ]);
    }
    
    public function update($url_path , $username , Request $request){
        if(is_null(Session::get('username'))) return redirect('/');

        $this->validate($request,[
            'password' => 'required',
            'name' => 'required',
            'role' => 'required'
        ]);

        $user = User::where('user_username' , '=' , $username)->first();

        $user->user_password = $request->password;
        $user->user_name = $request->name;
        $user->bidang_id = $request->role;
        $user->save();

        return redirect('/'. Session::get('rolePrefix') .'/view/user');
    }

    public function delete($bidangPrefix, $username){
        $user = User::where('user_username' , '=' , $username)->first();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();

        $user->delete();

        return redirect('/'. Session::get('rolePrefix') .'/view/user');
    }

    public function getFolderPath($bidangPrefix, $url_path){
        $base_path = 'public/'.$bidangPrefix;

        if(count((explode('/', $url_path))) > 1){
            $split = explode('/', $url_path, -1);
            $merge = implode('/', $split);

            return $base_path. '/'. $merge;
        } else {
            return $base_path;
        }
    }

    private function updateUrlPath($oldUrl, $newFoldername){
        if(!is_null($oldUrl)){
            if(count((explode('/', $oldUrl))) > 1){
                $split = explode('/', $oldUrl, -1);
                $merge = implode('/', $plit);
                return $merge .'/'. $newFoldername;
            } else {
                return $newFoldername;
            }
        }

        return null;
    }

    public function deleteUrlPathLast($url_path){
        if(count((explode('/', $url_path))) > 1){
            $split = explode('/', $url_path, -1);
            $merge = implode('/', $split);

            return $merge;
        } else {
            return null;
        }
    }

    public function logout(){
        Session::flush();
        return redirect('/');
    }
}
