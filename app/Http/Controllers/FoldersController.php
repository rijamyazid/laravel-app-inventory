<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Role;
use App\Folder;
use App\File;
use Alert;

class FoldersController extends Controller
{

    public function create($role_prefix, $url_path=''){
        // return view('admin.create_table', ['url_path'=> $url_path,'role' => $role_prefix]);
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        $folders = Folder::where('parent_path', 'public/' . $role_prefix)->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('folder_role', '=', $role_prefix)->get();
                
        return view('content.folders.create', 
            ['url_path'=> $url_path, 
            'role' => $role_prefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function store($role_prefix, $url_path='', Request $request){

        $base_path = 'public/' . $role_prefix;

        $this->validate($request,[
            'folder_name' => 'required',
        ]);

        $newFolderName = $request->folder_name;

        $folders = Folder::where('name', 'like', $request->folder_name . '%')->get();
        if(count($folders) > 0){
            $newFolderName = $request->folder_name . ' ('. count($folders) .')';
        }

        $url_path_new = $url_path;
        if($url_path == ''){
            $url_path_new = $request->folder_name;
            Storage::makeDirectory($base_path. '/' .$url_path_new);
            Folder::create([
                'name' => $newFolderName,
                'url_path' => $url_path_new,
                'parent_path' => self::getFolderPath($role_prefix, $url_path_new),
                'created_by' => Session::get('username'),
                'folder_role' => $role_prefix
            ]);
            Alert::success('Folder Berhasil Ditambah!');
            return redirect('/'.$role_prefix.'/folder/');
        } else {
            Storage::makeDirectory($base_path. '/' .$url_path_new.'/'.$request->folder_name);
            Folder::create([
                'name' => $newFolderName,
                'url_path' => $url_path_new.'/'.$request->folder_name,
                'parent_path' => self::getFolderPath($role_prefix, $url_path_new.'/'.$request->folder_name),
                'created_by' => Session::get('username'),
                'folder_role' => $role_prefix
            ]);
            Alert::success('Folder Berhasil Ditambah!');
            return redirect('/'.$role_prefix.'/folder/'.$url_path_new.'/');
        }
    }

    public function view($role_prefix, $url_path=''){
        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        if($url_path == ''){
            $folders = Folder::where('parent_path', 'public/'.$role_prefix)
                ->orderBy('name', 'asc')->get();
            $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('parent_path', '=', 'public')
                ->where('folder_role', $role_prefix)
                ->orderBy('filename', 'asc')->get();
            // $files = File::with('folders')->where('parent_path', 'public')->where('folder_role', $role_prefix)->get();
        } else {
            $folders = Folder::where('parent_path', 'public/'.$role_prefix.'/'.$url_path)
                ->orderBy('name', 'asc')->get();
            $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('url_path', '=', $url_path)
                ->orderBy('filename', 'asc')->get();
            // $files = File::with('folders')->where('url_path', $url_path)->get();
        }

        return view('content.folders.view', 
            ['url_path'=> $url_path,
            'locations' => self::locations($url_path), 
            'role' => $role_prefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function edit($role_prefix, $folderID){
        $roles = Role::orderBy('role', 'asc')->get();
        $folder = Folder::find($folderID);
        $sessions = Session::all();
        return view('content.folders.edit', 
            ['folder'=>$folder, 
            'role' => $role_prefix,
            'sessions' => $sessions,
            'roles' => $roles]);
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

        Alert::success('Folder Berhasil Di Edit!');
        return redirect('/'. $role_prefix .'/folder/'. self::deleteUrlPathLast($oldUrlPath));
    }

    public function delete($role_prefix, $folder_id){
        $folder = Folder::find($folder_id);

        Storage::deleteDirectory($folder->parent_path.'/'.$folder->name);
        $tmpFolderLastPath = $folder->url_path;
        $folder->delete();
        return redirect('/'.$role_prefix.'/folder/'.self::deleteUrlPathLast($tmpFolderLastPath));
    }

    public function search($role, Request $request){
        $this->validate($request,[
            'q' => 'required',
        ]);

        $sessions = Session::all();
        $roles = Role::orderBy('role', 'asc')->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
            ->where('filename', 'like', $request->q . '%')
            ->where('folders.folder_role', '=', $request->bidang)
            ->orderBy('filename', 'asc')->get();

        return view('content.folders.view', 
            ['url_path'=> '', 
            'role' => $role,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => [],
            'files' => $files]);
    }

    public function createNewBidang($role, Request $request)
    {
        $this->validate($request,[
            'foldername' => 'required',
        ]);

        $roleName = $request->foldername;
        $rolePrefix = self::getRolePrefix($roleName);

        Storage::makeDirectory('public/' . $rolePrefix);
        Folder::create([
            'name' => $rolePrefix,
            'parent_path' => 'public',
            'created_by' => Session::get('username'),
            'folder_role' => $rolePrefix
        ]);
        Role::create([
            'role' => $roleName,
            'role_prefix' => $rolePrefix
        ]);

        return redirect('/' . $rolePrefix . '/folder');
    }

    private function getRolePrefix($roleName){
        $split = explode(' ', strtolower($roleName));
        return implode('_', $split);
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

    public function locations($location){
        $split = explode('/', $location);
        $darray = array();
        $locLink = '';
        foreach($split as $s){
            $locLink = $locLink . $s . '/';
            array_push($darray, array('loc' => $s, 'locLink' => $locLink));            
        }
        return $darray;
    }
}
