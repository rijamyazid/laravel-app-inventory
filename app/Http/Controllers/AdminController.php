<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Folder;
use App\File;
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
        $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('parent_path', '=', 'public')->get();

        return view('admin.table', ['url_path'=> '', 'role' => $role_prefix ,'folders' => $folders, 'files' => $files]);
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
        if($url_path == ''){
            $url_path_new = $request->folder_name;
            Storage::makeDirectory($base_path.$url_path_new);
            Folder::create([
                'name' => $request->folder_name,
                'url_path' => $url_path_new,
                'parent_path' => self::getFolderPath($role_prefix, $url_path_new),
                'created_by' => Session::get('user'),
                'folder_role' => $role_prefix
            ]);
            return redirect('/'.$role_prefix.'/folder/');
        } else {
            Storage::makeDirectory($base_path.$url_path_new.'/'.$request->folder_name);
            Folder::create([
                'name' => $request->folder_name,
                'url_path' => $url_path_new.'/'.$request->folder_name,
                'parent_path' => self::getFolderPath($role_prefix, $url_path_new.'/'.$request->folder_name),
                'created_by' => Session::get('user'),
                'folder_role' => $role_prefix
            ]);
            return redirect('/'.$role_prefix.'/folder/'.$url_path_new.'/');
        }
    }

    public function deleteFolder($role_prefix, $folder_id){
        $tmpFolderLastPath = '';

        $folder = Folder::find($folder_id);

        Storage::deleteDirectory($folder->parent_path.'/'.$folder->name);
        $tmpFolderLastPath = $folder->url_path;
        $folder->delete();
        return redirect('/'.$role_prefix.'/folder/'.self::deleteUrlPathLast($tmpFolderLastPath));
    }

    public function view($role_prefix, $url_path=''){
        $folders = Folder::where('parent_path', 'public/'.$role_prefix.'/'.$url_path)->get();

        if($url_path == ''){
            $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('parent_path', '=', 'public')
                ->where('folder_role', $role_prefix)->get();
            // $files = File::with('folders')->where('parent_path', 'public')->where('folder_role', $role_prefix)->get();
        } else {
            $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('url_path', '=', $url_path)->get();
            // $files = File::with('folders')->where('url_path', $url_path)->get();
        }

        return view('admin.table', ['url_path'=> $url_path, 'role' => $role_prefix ,'folders' => $folders, 'files' => $files]);
    }

    public function logout(){
        Session::flush();
        return redirect('/');
    }

    public function getFolderPath($role_prefix, $url_path){
        $base_path = 'public/'.$role_prefix.'/';

        if(count((explode('/', $url_path))) > 1){
            $split = explode('/', $url_path, -1);
            $merge = implode('/', $split);

            return $base_path.$merge;
        } else {
            return $base_path;
        }
    }

    public function deleteUrlPathLast($url_path){
        if(count((explode('/', $url_path))) > 1){
            $split = explode('/', $url_path, -1);
            $merge = implode($split);

            return $merge;
        } else {
            return '';
        }
    }
}
