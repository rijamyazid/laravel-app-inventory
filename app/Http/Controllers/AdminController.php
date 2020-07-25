<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Folder;
use App\File;
use App\Role;
use App\Admin;

class AdminController extends Controller
{

    public function index($role_prefix){
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();

        return view('content.index', 
                [ 'roleS' => Session::get('role'), 
                    'role' => $role_prefix,
                    'sessions' => $sessions,
                    'roles' => $roles,]);
    }

    public function viewAdmin(){
        $sessions = Session::all();
        $admin = Admin::get();
        $roles = Role::orderBy('role', 'asc')->get();
        return view('content.admin.view', 
            ['admin' => $admin ,
             'sessions' => $sessions ,
             'roles' => $roles]);
    }

    public function createAdmin(){
        $admin = Admin::get();
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        return view('content.admin.create', 
            ['admin' => $admin,
             'sessions' => $sessions,
             'roles' => $roles]);
    }

    public function storeAdmin(Request $request){
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

        Admin::create([
            'username' => $username,
            'password' => $password,
            'name' => $name,
            'role_id' => $role,
        ]);

        return redirect('/'. Session::get('rolePrefix') .'/view/admin');
    }

    public function editAdmin($role_prefix, $username){
        $admin = Admin::where('username' , '=' , $username)->first();
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        
        return view('content.admin.edit', 
            ['admin' => $admin,
             'sessions' => $sessions,
             'roles' => $roles] );
    }
    
    public function updateAdmin($url_path , $username , Request $request){
        // $this->validate($request,[
        //     'username' => 'required',
        //     'password' => 'required',
        //     'name' => 'required',
        // ]);

        $admin = Admin::where('username' , '=' , $username)->first();

        $admin->password = $request->password;
        $admin->name = $request->name;
        $admin->role_id = $request->role;
        $admin->save();

        return redirect('/'. Session::get('rolePrefix') .'/view/admin');
    }

    public function deleteAdmin($role_prefix, $username){
        $admin = Admin::where('username' , '=' , $username)->first();
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();

        $admin->delete();

        return redirect('/'. Session::get('rolePrefix') .'/view/admin');
    }

    public function getFolderPath($role_prefix, $url_path){
        $base_path = 'public/'.$role_prefix;

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
