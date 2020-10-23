<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Helper;
use App\User;
use App\Bidang;
use App\Folder;
use App\File;
use Alert;

class FoldersController extends Controller
{

    public function create($bidangPrefix, $url_path=''){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

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

    public function store($bidangPrefix, $url_path = null, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        if(is_null($request->folder_name) || empty($request->folder_name)){
            Session::flash('alert-danger', 'Nama folder tidak boleh kosong!');
            return redirect("/$bidangPrefix/folder/$url_path");
        } else if(count(
                    Folder::where('folder_name', '=', $request->folder_name)
                        ->where('parent_path', '=', self::getFolderPath($bidangPrefix, $url_path))
                        ->where('folder_status', '=', 'available')
                        ->get()
                ) > 0){
            Session::flash('alert-danger', 'Di folder ini sudah ada folder dengan nama yang sama!');
            return redirect("/$bidangPrefix/folder/$url_path");
        } else if($request->folder_flag == 'pilih' && is_null($request->folder_flag_bidang)){
            Session::flash('alert-danger', 'Pilih minimal satu bidang untuk diberi hak akses!');
            return redirect("/$bidangPrefix/folder/$url_path");
        }
        // if(is_null($request->folder_name) || empty($request->folder_name)) throw ValidationException::withMessages(['folder_name' => 'Nama folder tidak boleh kosong!']);
        // if($request->folder_flag == 'pilih' && is_null($request->folder_flag_bidang)) throw ValidationException::withMessages(['folder_flag_bidang' => 'Pilih minimal satu bidang untuk diberi hak akses']);

        $base_path = 'public/' . $bidangPrefix;
        $folderFlag = 'public';

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

        if(is_null($url_path)) { 
            $url_path_new = $request->folder_name;
        } else {
            $url_path_new = $url_path . '/'. $request->folder_name;
        }

        Storage::makeDirectory($base_path. '/' .$url_path_new);
        Folder::create([
            'folder_name' => $newFolderName,
            'url_path' => $url_path_new,
            'parent_path' => self::getFolderPath($bidangPrefix, $url_path_new),
            'folder_flag' => $folderFlag,
            'user_id' => Helper::getUserByUsername(Session::get('username'))->id,
            'bidang_id' => Helper::getBidangByPrefix($bidangPrefix)->id
        ]);
        
        Session::flash('alert-success', 'Folder berhasil ditambahkan!');
        return redirect('/'.$bidangPrefix.'/folder/'.$url_path);
    }

    public function view($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        }

        Session::put('side_loc', $bidangPrefix);
        Session::put('move_folderNameGoal', Helper::getFolderByUrl($urlPath, $bidangPrefix)->folder_name);

        $sessions = Session::all();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
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
                ->where('folder_status','=','available')
                ->orderBy('folder_name', 'asc')->get();
            $files = File::where('folder_id', '=', Helper::getFolderByUrl($urlPath, $bidangPrefix)->id)
                    ->where(function($query) use ($sessions){
                    $query->where('file_flag', '=', 'public')
                        ->orWhere('file_flag', 'like', '%'. $sessions['rolePrefix'] .'%');
                    })
                    ->where('file_status','=','available')
                ->orderBy('file_name', 'asc')->get();
            // $files = File::with('folders')->where('url_path', $url_path)->get();
        // }

        return view('content.folders.view', 
            ['urlPath'=> $urlPath,
            'bidangPrefix' => $bidangPrefix,
            'bidangS' => $bidangS,
            'folders' => $folders, 
            'files' => $files]);
    }

    public function edit($bidangPrefix, $folderID){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
        $folder = Folder::find($folderID);
        $sessions = Session::all();
        return view('content.folders.edit', 
            ['folder'=>$folder, 
            'flags' => Helper::getFlags($folder->folder_flag),
            'bidangPrefix' => $bidangPrefix,
            'bidangS' => $bidangS]);
    }

    public function update($bidangPrefix, $folderID, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }
        // $this->validate($request,[
        //     'folder_name' => 'required',
        // ]);

        $folder = Folder::find($folderID);
        if(is_null($request->folder_name) || empty($request->folder_name)){
            Session::flash('alert-danger', 'Nama folder tidak boleh kosong!');
            return redirect("/$bidangPrefix/edit/folder/$folderID");
        } else if(count(
                    Folder::where('folder_name', '=', $request->folder_name)
                        ->where('parent_path', '=', $folder->parent_path)
                        ->where('folder_status', '=', 'available')
                        ->get()
                ) > 0){
            Session::flash('alert-danger', 'Di folder ini sudah ada folder dengan nama yang sama!');
            return redirect("/$bidangPrefix/edit/folder/$folderID");
        } else if($request->folder_flag == 'pilih' && is_null($request->folder_flag_bidang)){
            Session::flash('alert-danger', 'Pilih minimal satu bidang untuk diberi hak akses!');
            return redirect("/$bidangPrefix/edit/folder/$folderID");
        }

        $folderFlag = 'public';
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
        $pos = strpos($oldUrlPath, $oldFolderName);
        if ($pos !== false) {
            $newUrlPath = substr_replace($oldUrlPath, $newFolderName, $pos, strlen($oldFolderName));
        }

        if($oldFolderName != $newFolderName) Storage::move($folder->parent_path .'/'. $oldFolderName, $folder->parent_path .'/'. $newFolderName);

        $folderTrashed = Folder::where('folder_name', '=', $oldFolderName.'_trashed')
                            ->where('parent_path', '=', self::getFolderPath($bidangPrefix, $oldUrlPath))
                            ->first();
        $folderTrashed->folder_name = $newFolderName.'_trashed';
        $folderTrashed->url_path = $newUrlPath;
        $folderTrashed->folder_flag = $folderFlag;
        $folderTrashed->save();

        $folder->folder_name = $newFolderName;
        $folder->url_path = $newUrlPath;
        $folder->folder_flag = $folderFlag;
        $folder->save();

        $folders = Folder::where('url_path', 'like', $oldUrlPath.'/%')->get();
        foreach($folders as $folder){
            $pos = strpos($folder->url_path, $oldFolderName);
            if ($pos !== false) {
                $newUrlPath = substr_replace($folder->url_path, $newFolderName, $pos, strlen($oldFolderName));
            }

            $pos = strpos($folder->parent_path, $oldFolderName);
            if ($pos !== false) {
                $newFolderPath = substr_replace($folder->parent_path, $newFolderName, $pos, strlen($oldFolderName));
            }

            $folder->url_path = $newUrlPath;
            $folder->parent_path = $newFolderPath;
            $folder->save();
        }

        Session::flash('alert-success', 'Folder berhasil diubah!');
        return redirect('/'. $bidangPrefix .'/folder/'. self::deleteUrlPathLast($oldUrlPath));
    }

    public function delete($bidangPrefix, $folderId){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $folder = Folder::find($folderId);
        $folders = Folder::where('url_path','like',"$folder->url_path/%")->get();
        $folderUrlPath = $folder->url_path;

        // Storage::deleteDirectory($folder->parent_path.'/'.$folder->folder_name);
        $folder->folder_status = 'trashed';

        $folderTrashed = Folder::where('folder_name', '=', $folder->folder_name.'_trashed')->first();
        if(is_null($folderTrashed)){
            Folder::create([
                'folder_name' => $folder->folder_name . '_trashed',
                'url_path' => $folder->url_path,
                'parent_path' => $folder->parent_path,
                'folder_status' => 'trashed',
                'folder_flag' => $folder->folder_flag,
                'user_id' => Helper::getUserByUsername(Session::get('username'))->id,
                'bidang_id' => $folder->bidang_id
            ]);
        }

        $folder->save();

        $files = File::where('folder_id', '=', $folderId)->get();
        foreach ($files as $_file) {
            $_file->file_status = 'trashed';
            $_file->save();
        }

        foreach($folders as $_folder){
            $_folder->folder_status = 'trashed';
            $_files = File::where('folder_id', '=', $_folder->id)->get();
            foreach ($_files as $__file) {
                $__file->file_status = 'trashed';
                $__file->save();
            }
            $_folder->save();
        }

        Session::flash('alert-success', 'Folder berhasil dihapus!');
        return redirect('/'.$bidangPrefix.'/folder/'.Helper::deleteUrlPathLast($folderUrlPath));
    }

    public function move($bidangPrefix, $folderId){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        Session::put('move_folderId', $folderId);
        Session::put('move_folderName', Folder::find($folderId)->folder_name);
        Session::forget('move_fileId');

        $urlPath = self::deleteUrlPathLast(Helper::getFolderById($folderId)->url_path);

        return redirect('/'.$bidangPrefix.'/folder/'.$urlPath);
    }

    public function moving($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        /** Folder yang akan dipindahkan (F1) */
        $oldfolder = Folder::find(Session::get('move_folderId'));
        /** Folder lokasi tujuan (F2) */
        $newFolder = Helper::getFolderByUrl($urlPath, $bidangPrefix);

        /** Simpan parent path dari folder (F1) */
        $oldParentPath = $oldfolder->parent_path;

        /** Ambil semua data folder dan sub-folder didalam folder (F1) -> (F11) */
        $folders = Folder::where('url_path', 'like', $oldfolder->url_path . '/%')->get();

        /** Pindahkan folder lokal (F1) ke folder tujuan (F2) */
        Storage::move($oldfolder->parent_path .'/'. $oldfolder->folder_name, $newFolder->parent_path .'/'. $newFolder->folder_name .'/'. $oldfolder->folder_name);
        /** Rubah parent_path folder (F1) dengan parent_path dari folder tujuan (F2) */
        $oldfolder->url_path = Helper::getUrlFromParentPath($bidangPrefix, $oldfolder->folder_name, $newFolder->parent_path . '/' . $newFolder->folder_name);
        $oldfolder->parent_path = $newFolder->parent_path . '/' . $newFolder->folder_name;
        $oldfolder->save();
        
        /** Ambil folder (F1) yang sudah diperbaharui parent_path nya */
        $oldfolder = Folder::find(Session::get('move_folderId'));
        
        /** (F11) */
        foreach($folders as $folder){
            /** 
             * Mengubah parent path pada folder dan sub-folder dengan parent path baru
             * Caranya dengan menimpa parent path (F1) yang lama dengan parent path (F1) yang baru
             */
            $pos = strpos($folder->parent_path, $oldParentPath);
            if ($pos !== false) {
                $parentPathNew = substr_replace($folder->parent_path, $oldfolder->parent_path, $pos, strlen($oldParentPath));
            }

            /** Konversi parent_path kedalam bentuk url, kemudian update url baru pada folder */
            $folder->url_path = Helper::getUrlFromParentPath($bidangPrefix, $folder->folder_name, $parentPathNew);
            /** Ganti parent_path pada folder dengan yang sudah diperbaharui */
            $folder->parent_path = $parentPathNew;
            $folder->save();
        }

        Session::forget('move_folderId');

        Session::flash('alert-success', 'Folder berhasil dipindahkan!');
        return redirect('/'.$bidangPrefix.'/folder/'. $urlPath);
    }

    public function moveCancel($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        Session::forget('move_folderId');

        Session::flash('alert-warning', 'Pemindahan folder dibatalkan!');
        return redirect('/'.$bidangPrefix.'/folder/'. $urlPath);
    }

    public function search($bidangPrefix, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        }

        $this->validate($request,[
            'q' => 'required',
        ]);

        $sessions = Session::all();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
            ->join('bidang', 'folders.bidang_id', '=', 'bidang.id')
            ->where('file_name', 'like', $request->q . '%')
            ->where('bidang.bidang_prefix', '=', $bidangPrefix)
            ->orderBy('file_name', 'asc')->get();

        return view('content.folders.view', 
            ['urlPath'=> null,
            'bidangPrefix' => $bidangPrefix,
            'bidangS' => $bidangS,
            'folders' => [],
            'files' => $files]);
    }

    public function getFolderPath($bidangPrefix, $url_path){
        $base_path = 'public/'.$bidangPrefix;

        if($url_path != null && count((explode('/', $url_path))) > 1){
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
