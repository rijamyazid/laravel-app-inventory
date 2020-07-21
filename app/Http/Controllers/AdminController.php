<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Folder;
use App\File;
use App\Role;

class AdminController extends Controller
{

    public function index($role_prefix){

        if(!Session::has('role')){
            Session::put('role', 'Guest');
            Session::put('rolePrefix', 'guest');
            Session::save();

            return redirect('/guest');
        }

        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        $folders = Folder::where('parent_path', 'public/' . $role_prefix)->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('parent_path', '=', 'public')
                ->where('folder_role', $role_prefix)->get();;

        return view('admin.table', 
            ['url_path'=> '', 
            'role' => $role_prefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function frontPage($role_prefix){
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();

        return view('admin.index', 
                [ 'roleS' => Session::get('role'), 
                    'role' => $role_prefix,
                    'sessions' => $sessions,
                    'roles' => $roles,]);
    }

    public function createFolder($role_prefix, $url_path=''){
        // return view('admin.create_table', ['url_path'=> $url_path,'role' => $role_prefix]);
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        $folders = Folder::where('parent_path', 'public/' . $role_prefix)->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('folder_role', '=', $role_prefix)->get();
                
        return view('admin.create_table', 
            ['url_path'=> $url_path, 
            'role' => $role_prefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function createFolderProcess($role_prefix, $url_path='', Request $request){

        $base_path = 'public/' . $role_prefix;

        $this->validate($request,[
            'folder_name' => 'required',
        ]);

        $url_path_new = $url_path;
        if($url_path == ''){
            $url_path_new = $request->folder_name;
            Storage::makeDirectory($base_path. '/' .$url_path_new);
            Folder::create([
                'name' => $request->folder_name,
                'url_path' => $url_path_new,
                'parent_path' => self::getFolderPath($role_prefix, $url_path_new),
                'created_by' => Session::get('username'),
                'folder_role' => $role_prefix
            ]);
            return redirect('/'.$role_prefix.'/folder/');
        } else {
            Storage::makeDirectory($base_path. '/' .$url_path_new.'/'.$request->folder_name);
            Folder::create([
                'name' => $request->folder_name,
                'url_path' => $url_path_new.'/'.$request->folder_name,
                'parent_path' => self::getFolderPath($role_prefix, $url_path_new.'/'.$request->folder_name),
                'created_by' => Session::get('username'),
                'folder_role' => $role_prefix
            ]);
            return redirect('/'.$role_prefix.'/folder/'.$url_path_new.'/');
        }
    }

    public function deleteFolder($role_prefix, $folder_id){
        $folder = Folder::find($folder_id);

        Storage::deleteDirectory($folder->parent_path.'/'.$folder->name);
        $tmpFolderLastPath = $folder->url_path;
        $folder->delete();
        return redirect('/'.$role_prefix.'/folder/'.self::deleteUrlPathLast($tmpFolderLastPath));
    }

    public function edit($role_prefix, $folderID){
        $folder = Folder::find($folderID);
        return view('admin.folder.edit', ['folder'=>$folder, 'role' => $role_prefix]);
    }

    public function update($role_prefix, $folderID, Request $request){
        $this->validate($request,[
            'foldername' => 'required',
        ]);

        $folder = Folder::find($folderID);

        $oldFolderName = $folder->name;
        $oldUrlPath = $folder->url_path;
        $newFolderName = $request->foldername;

        Storage::move($folder->parent_path .'/'. $oldFolderName, $folder->parent_path .'/'. $newFolderName);

        $folder->name = $newFolderName;
        $folder->save();

        $folders = Folder::where('url_path', 'like', $folder->url_path . '%')->get();
        foreach($folders as $folder){
            $folder->url_path = Str::of($folder->url_path)->replaceFirst($oldFolderName, $newFolderName);
            $folder->parent_path = Str::of($folder->parent_path)->replaceFirst($oldFolderName, $newFolderName);
            $folder->save();
        }

        return redirect('/'. $role_prefix .'/folder/'. self::deleteUrlPathLast($oldUrlPath));
    }

    public function view($role_prefix, $url_path=''){
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        if($url_path == ''){
            $folders = Folder::where('parent_path', 'public/'.$role_prefix)->get();
            $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('parent_path', '=', 'public')
                ->where('folder_role', $role_prefix)->get();
            // $files = File::with('folders')->where('parent_path', 'public')->where('folder_role', $role_prefix)->get();
        } else {
            $folders = Folder::where('parent_path', 'public/'.$role_prefix.'/'.$url_path)->get();
            $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('url_path', '=', $url_path)->get();
            // $files = File::with('folders')->where('url_path', $url_path)->get();
        }

        return view('admin.table', 
            ['url_path'=> $url_path, 
            'role' => $role_prefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function logout(){
        Session::flush();
        return redirect('/');
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
}
