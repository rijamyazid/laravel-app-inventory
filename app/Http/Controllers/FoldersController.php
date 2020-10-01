<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Helper;
use App\Admin;
use App\Bidang;
use App\Folder;
use App\File;
use Alert;

class FoldersController extends Controller
{

    public function create($bidangPrefix, $url_path=''){
        // return view('admin.create_table', ['url_path'=> $url_path,'role' => $role_prefix]);
        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        $folders = Folder::where('parent_path', 'public/' . $bidangPrefix)->get();
        // $files = File::join('folders', 'folder_id','=', 'folders.id')
        //         ->where('bidang_id', '=', \Helper::getBidangByPrefix($bidangPrefix)->id)->get();
                
        return view('content.folders.create', 
            ['url_path'=> $url_path, 
            'role' => $bidangPrefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders
            ]);
    }

    public function store($bidangPrefix, $url_path='', Request $request){

        $base_path = 'public/' . $bidangPrefix;
        $folderFlag = 'public';

        if(is_null($request->folder_name) || empty($request->folder_name)) throw ValidationException::withMessages(['folder_name' => 'Nama folder tidak boleh kosong!']);
        if($request->folder_flag == 'pilih' && is_null($request->folder_flag_bidang)) throw ValidationException::withMessages(['folder_flag_bidang' => 'Pilih minimal satu bidang untuk diberi hak akses']);

        $newFolderName = $request->folder_name;
        if($request->folder_flag == 'private'){
            $folderFlag = 'super_admin,'.$bidangPrefix;
        } else if($request->folder_flag == 'pilih'){
            $folderFlag = 'super_admin,'.$bidangPrefix;
            $ffbS = $request->folder_flag_bidang;
            foreach ($ffbS as $ffb) {
                $folderFlag .= ',' . $ffb;
            }
        }
 
        if(empty($url_path)) $url_path_new = $request->folder_name;
        else $url_path_new = $url_path . '/'. $request->folder_name;

        Storage::makeDirectory($base_path. '/' .$url_path_new);
        Folder::create([
            'folder_name' => $newFolderName,
            'url_path' => $url_path_new,
            'parent_path' => self::getFolderPath($bidangPrefix, $url_path_new),
            'folder_flag' => $folderFlag,
            'admin_id' => Helper::getAdminByUsername(Session::get('username'))->id,
            'bidang_id' => Helper::getBidangByPrefix($bidangPrefix)->id
        ]);
        Alert::success('Folder Berhasil Ditambah!');
        return redirect('/'.$bidangPrefix.'/folder/'.$url_path);
    }

    public function view($bidangPrefix, $urlPath = null){
        Session::put('side_loc', $bidangPrefix);

        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        $bidang = Bidang::where('bidang_prefix', '=', $bidangPrefix)->first();
        // if($url_path == ''){
        //     $folders = Folder::where('parent_path', 'public/'.$bidangPrefix)
        //         ->orderBy('folder_name', 'asc')->get();
        //     $files = File::join('folders', 'folder_id', '=', 'folders.id')
        //         ->where('parent_path', '=', 'public')
        //         ->where('bidang_id', $bidang->id)
        //         ->orderBy('file_name', 'asc')->get();
        //     // $files = File::with('folders')->where('parent_path', 'public')->where('folder_role', $role_prefix)->get();
        // } else {
            if(is_null($urlPath)) $url_path_new = 'public/'.$bidangPrefix;
            else $url_path_new = 'public/'.$bidangPrefix.'/'.$urlPath;

            $folders = Folder::where('parent_path', '=', $url_path_new)
                ->where(function($query) use ($sessions){
                    $query->where('folder_flag', '=', 'public')
                        ->orWhere('folder_flag', 'like', '%'. $sessions['rolePrefix'] .'%');
                })
                ->orderBy('folder_name', 'asc')->get();

            $files = File::where('folder_id', '=', Helper::getFolderByUrl($urlPath, $bidangPrefix)->id)
                    ->where(function($query) use ($sessions){
                    $query->where('file_flag', '=', 'public')
                        ->orWhere('file_flag', 'like', '%'. $sessions['rolePrefix'] .'%');
                })
                ->orderBy('file_name', 'asc')->get();
            // $files = File::with('folders')->where('url_path', $url_path)->get();
        // }

        $data = array('url' => $urlPath);

        return view('content.folders.view', 
            ['url_path'=> $urlPath,
            'role' => $bidangPrefix,
            'sessions' => $sessions,
            'roles' => $roles,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function modal($bidangPrefix, $id, Request $request){
        $folder = Folder::find($id);
        return view('content.folders.modal', compact('folder'))->renderSections()['sub-content'];
    }

    public function edit($bidangPrefix, $folderID){
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        $folder = Folder::find($folderID);
        $sessions = Session::all();
        return view('content.folders.edit', 
            ['folder'=>$folder, 
            'flags' => self::getFlags($folder->folder_flag),
            'role' => $bidangPrefix,
            'sessions' => $sessions,
            'roles' => $roles]);
    }

    private function getFlags($flags){
        return explode(',', $flags);
    }

    public function update($bidangPrefix, $folderID, Request $request){
        $this->validate($request,[
            'folder_name' => 'required',
        ]);

        $folder = Folder::find($folderID);

        if(is_null($request->folder_name) || empty($request->folder_name)) throw ValidationException::withMessages(['folder_name' => 'Nama folder tidak boleh kosong!']);
        if($request->folder_flag == 'pilih' && is_null($request->folder_flag_bidang)) throw ValidationException::withMessages(['folder_flag_bidang' => 'Pilih minimal satu bidang untuk diberi hak akses']);

        if($request->folder_flag == 'private'){
            $folderFlag = 'super_admin,'.$bidangPrefix;
        } else if($request->folder_flag == 'pilih'){
            $folderFlag = 'super_admin,'.$bidangPrefix;
            $ffbS = $request->folder_flag_bidang;
            foreach ($ffbS as $ffb) {
                $folderFlag .= ',' . $ffb;
            }
        }

        $oldFolderName = $folder->folder_name;
        $oldUrlPath = $folder->url_path;
        $newFolderName = $request->folder_name;

        Storage::move($folder->parent_path .'/'. $oldFolderName, $folder->parent_path .'/'. $newFolderName);

        $folder->folder_name = $newFolderName;
        $folder->folder_flag = $folderFlag;
        $folder->save();

        $folders = Folder::where('url_path', 'like', $folder->url_path . '%')->get();
        foreach($folders as $folder){
            $folder->url_path = Str::of($folder->url_path)->replaceFirst($oldFolderName, $newFolderName);
            $folder->parent_path = Str::of($folder->parent_path)->replaceFirst($oldFolderName, $newFolderName);
            $folder->save();
        }

        Alert::success('Folder Berhasil Di Edit!');
        return redirect('/'. $bidangPrefix .'/folder/'. self::deleteUrlPathLast($oldUrlPath));
    }

    public function delete($bidangPrefix, $folderId){
        $folder = Folder::find($folderId);

        Storage::deleteDirectory($folder->parent_path.'/'.$folder->folder_name);
        $tmpFolderLastPath = $folder->url_path;
        $folder->delete();
        Alert::warning('Folder Berhasil Dihapus!');
        return redirect('/'.$bidangPrefix.'/folder/'.self::deleteUrlPathLast($tmpFolderLastPath));
    }

    public function move($bidangPrefix, $folderId){
        Session::put('move', $folderId);

        $urlPath = self::deleteUrlPathLast(Helper::getFolderById($folderId)->url_path);

        return redirect('/'.$bidangPrefix.'/folder/'.self::deleteUrlPathLast($urlPath));

    }

    public function moving($bidangPrefix, $urlPath = null){


        $oldfolder = Folder::find(Session::get('move'));
        $newFolder = Helper::getFolderByUrl($urlPath, $bidangPrefix);

        $oldUrlPathPos = strpos($oldfolder->url_path, $oldfolder->folder_name);
        if($oldUrlPathPos) $oldUrlPath = substr($oldfolder->url_path, 0, $oldUrlPathPos-1);
        else $oldUrlPath = substr($oldfolder->url_path, 0);

        $oldParentPath = $oldfolder->parent_path;

        $folders = Folder::where('url_path', 'like', $oldfolder->url_path . '%')->get();

        $oldfolder->parent_path = $newFolder->parent_path . '/' . $newFolder->folder_name;
        $oldfolder->save();

        $oldfolder = Folder::find(Session::get('move'));
        foreach($folders as $folder){
            $folder->url_path = Helper::getUrlFromParentPath($bidangPrefix, $folder->folder_name, Str::of($folder->parent_path)->replaceFirst($oldParentPath, $oldfolder->parent_path));
            $folder->parent_path = Str::of($folder->parent_path)->replaceFirst($oldParentPath, $oldfolder->parent_path);
            $folder->save();
        }

        Session::forget('move');

        return redirect('/'.$bidangPrefix.'/folder/'. $urlPath);
    }

    public function search($bidangPrefix, Request $request){
        $this->validate($request,[
            'q' => 'required',
        ]);

        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
            ->join('bidang', 'folders.bidang_id', '=', 'bidang.id')
            ->where('file_name', 'like', $request->q . '%')
            ->where('bidang.bidang_prefix', '=', $bidangPrefix)
            ->orderBy('file_name', 'asc')->get();

        return view('content.folders.view', 
            ['url_path'=> null,
            'role' => $bidangPrefix,
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

        $admin = Admin::where('admin_username', '=', Session::get('username'))->first();
        Storage::makeDirectory('public/' . $rolePrefix);
        Bidang::create([
            'bidang_name' => $roleName,
            'bidang_prefix' => $rolePrefix
        ]);

        $bidang = Bidang::where('bidang_prefix', '=', $rolePrefix)->first();
        Folder::create([
            'folder_name' => $rolePrefix,
            'parent_path' => 'public',
            'admin_id' => $admin->id,
            'bidang_id' => $bidang->id
        ]);
        

        return redirect('/' . $rolePrefix . '/folder');
    }

    private function getRolePrefix($roleName){
        $split = explode(' ', strtolower($roleName));
        return implode('_', $split);
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
}
