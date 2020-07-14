<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Folder;
use App\Role;

class AdminController extends Controller
{

    public function index($role_prefix){

        if(Session::has('role')){
            if(Session::get('role') != $role_prefix){
                return redirect(Session::get('role'));
            }
        } else {
            Session::flush();
            return redirect('/');
        }

        $folders = Folder::where('parent_path', 'public/'.$role_prefix.'/')->get();

        return view('admin.table', ['url_path'=> 'root', 'role' => $role_prefix ,'folders' => $folders]);
    }

    public function createFolder($role_prefix, $url_path=''){
        return view('admin.create_table', ['url_path'=> $url_path,'role' => $role_prefix]);
    }

    public function createFolderProcess($role_prefix, $url_path='', Request $request){

        $base_path = 'public/'.$role_prefix.'/';

        $this->validate($request,[
            'folder_name' => 'required',
        ]);

        $url_path_new = $url_path;
        if($url_path == 'root'){
            $url_path_new = $request->folder_name;
        }

        Storage::makeDirectory($base_path.$url_path_new);

        // $username = Session::get('user');
        // echo $username;

        Folder::create([
            'name' => $request->folder_name,
            'url_path' => $url_path_new,
            'parent_path' => self::getFolderPath($role_prefix, $url_path_new),
            'created_by' => Session::get('user'),
            'folder_role' => $role_prefix
            ]);

        return redirect('/'.$role_prefix);
    }

    public function getFolderPath($role_prefix, $url_path){
        $base_path = 'public/'.$role_prefix.'/';

        if(count((explode('/', $url_path))) > 1){
            $split = explode('/', $url_path, -1);
            $merge = implode($split);

            return $base_path.$merge;
        } else {
            return $base_path;
        }
    }

    public function view(){
        
    }
}
