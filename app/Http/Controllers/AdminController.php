<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Folder;
use App\File;
use App\Bidang;
use App\Admin;

class AdminController extends Controller
{

    public function index($bidangPrefix){
        Session::put('side_loc', 'dashboard');

        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();

        return view('content.index', 
                [ 'roleS' => Session::get('role'), 
                    'role' => $bidangPrefix,
                    'sessions' => $sessions,
                    'roles' => $roles,]);
    }

    public function viewAdmin(){
        Session::put('side_loc', 'kelola_user');

        $sessions = Session::all();
        $admin = Admin::get();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();      

        return view('content.admin.view', 
            ['admin' => $admin ,
             'role' => 'super_admin',
             'sessions' => $sessions ,
             'roles' => $roles]);
    }

    public function createAdmin(){
        $admin = Admin::get();
        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        return view('content.admin.create', 
            ['admin' => $admin,
             'role' => 'super_admin',
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
            'admin_username' => $username,
            'admin_password' => $password,
            'admin_name' => $name,
            'bidang_id' => $role,
        ]);

        return redirect('/'. Session::get('rolePrefix') .'/view/admin');
    }

    public function editAdmin($bidangPrefix, $username){
        $admin = Admin::where('admin_username' , '=' , $username)->first();
        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        
        return view('content.admin.edit', 
            ['admin' => $admin,
             'role' => 'super_admin',
             'sessions' => $sessions,
             'roles' => $roles] );
    }
    
    public function updateAdmin($url_path , $username , Request $request){
        // $this->validate($request,[
        //     'username' => 'required',
        //     'password' => 'required',
        //     'name' => 'required',
        // ]);

        $admin = Admin::where('admin_username' , '=' , $username)->first();

        $admin->admin_password = $request->password;
        $admin->admin_name = $request->name;
        $admin->bidang_id = $request->role;
        $admin->save();

        return redirect('/'. Session::get('rolePrefix') .'/view/admin');
    }

    public function deleteAdmin($bidangPrefix, $username){
        $admin = Admin::where('admin_username' , '=' , $username)->first();
        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();

        $admin->delete();

        return redirect('/'. Session::get('rolePrefix') .'/view/admin');
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
