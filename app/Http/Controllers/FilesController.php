<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;  
use Helper;
use App\File;
use App\Folder;
use App\Bidang;
use Alert;

class FilesController extends Controller
{
    public function create($bidangPrefix, $url_path=''){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        // return view('admin.files.create', ['role' => $role_prefix, 'url_path' => $url_path]);
        $sessions = Session::all();
        $roles = Bidang::orderBy('bidang_name', 'asc')->get();
        $folders = Folder::where('parent_path', 'public/' . $bidangPrefix)->get();
        $files = File::join('folders', 'folder_id','=', 'folders.id')
                ->where('bidang_id', '=', \Helper::getBidangByPrefix($bidangPrefix)->id)->get();
                
        return view('content.files.create', 
            ['url_path' => $url_path, 
            'role'      => $bidangPrefix,
            'sessions'  => $sessions,
            'roles'     => $roles,
            'folders'   => $folders, 
            'files'     => $files]);
    }

    public function store($bidangPrefix, $url_path = null, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $basePath = 'public/' . $bidangPrefix;
        $fileFlag = 'public';

        if(!$request->hasFile('file_name')){
            Session::flash('alert-danger', 'Tambahkan minimal 1 file!');
            return redirect("/$bidangPrefix/folder/$url_path");
        }else if($request->file_flag == 'pilih' && is_null($request->file_flag_bidang)){
            Session::flash('alert-danger', 'Pilih minimal satu bidang untuk diberi hak akses!');
            return redirect("/$bidangPrefix/folder/$url_path");
        }
    
        if($request->file_flag == 'private'){
            $fileFlag = 'super_admin,'.$bidangPrefix;
        } else if($request->file_flag == 'pilih'){
            $fileFlag = 'super_admin,'.$bidangPrefix;
            $ffbS = $request->file_flag_bidang;
            foreach ($ffbS as $ffb) {
                $fileFlag .= ',' . $ffb;
            }
        }

        foreach ($request->file('file_name') as $file) {
            is_null($url_path) ? $storePath = Storage::putFile($basePath, $file) 
                : $storePath = Storage::putFile($basePath. '/'. $url_path, $file);

            $filename = $file->getClientOriginalName();
            // echo ($filename = $file->getClientOriginalName());
            $uuid = self::getUUID($storePath);
            // echo ($enc_filename = self::getUUID($path));
             
            File::create([
                'file_uuid' => $uuid,
                'file_name' => $filename,
                'file_flag' => $fileFlag,
                'user_id' => Helper::getUserByUsername(Session::get('username'))->id,
                'folder_id' => Helper::getFolderByUrl($url_path, $bidangPrefix)->id
            ]);
        }
        
        Session::flash('alert-success', count($request->file('file_name')).' File berhasil ditambahkan');
        return redirect($bidangPrefix . '/folder/' .$url_path);
    }

    public function edit($bidangPrefix, $uuid){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }
        
        return view('content.files.edit', 
            [
                'file'         => Helper::getFileByUUID($uuid),
                'flags'        => Helper::getFlags(Helper::getFileByUUID($uuid)->file_flag),
                'bidangS'      => Bidang::orderBy('bidang_name', 'asc')->get(), 
                'bidangPrefix' => $bidangPrefix
            ]);
    }

    public function update($bidangPrefix, $uuid, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $file = Helper::getFileByUUID($uuid);
        if($request->file_flag == 'pilih' && is_null($request->file_flag_bidang)){
            Session::flash('alert-danger', 'Pilih minimal satu bidang untuk diberi hak akses!');
            return redirect("/$bidangPrefix/edit/file/$uuid");
        }

        $fileFlag = 'public';
        if($request->file_flag == 'private'){
            $fileFlag = 'super_admin,'.$bidangPrefix;
        } else if($request->file_flag == 'pilih'){
            $fileFlag = 'super_admin,'.$bidangPrefix;
            $ffbS = $request->file_flag_bidang;
            foreach ($ffbS as $ffb) {
                $fileFlag .= ',' . $ffb;
            }
        }

        if($request->hasFile('file_name')){
            $filePath = $file->folder->parent_path . '/' . $file->folder->folder_name .'/'. $file->file_uuid;
            Storage::delete($filePath);

            $storePath = Storage::putFile($file->folder->parent_path . '/'. $file->folder->folder_name, $request->file('file_name'));
            $file->file_name = $request->file_name->getClientOriginalName();
            $file->file_uuid = self::getUUID($storePath);
        }
        $file->file_flag = $fileFlag;
        $file->save();

        Session::flash('alert-success', 'File berhasil diubah!');
        return redirect('/'. $bidangPrefix .'/folder/'. $file->folder->url_path);
    }

    public function move($bidangPrefix, $uuid) {
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        Session::put('move_fileId', $uuid);
        Session::put('move_fileName', Helper::getFileByUUID($uuid)->file_name);

        $urlPath = Helper::getFileByUUID($uuid)->folder->url_path;
        return redirect('/'.$bidangPrefix.'/folder/'.$urlPath);
    }

    public function moving($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }
        
        $file = Helper::getFileByUUID(Session::get('move_fileId'));
        $folder = Helper::getFolderByUrl($urlPath, $bidangPrefix);
        Storage::move($file->folder->parent_path .'/'. $file->folder->folder_name.'/'. $file->file_uuid, $folder->parent_path .'/'. $folder->folder_name .'/'. $file->file_uuid);
        $file->folder_id = $folder->id;
        $file->save();

        Session::forget('move_fileId');
        Session::forget('move_fileName');

        Session::flash('alert-success', 'File berhasil dipindahkan!');
        return redirect('/'.$bidangPrefix.'/folder/'. $urlPath);
    }

    public function moveCancel($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        Session::forget('move_fileId');
        Session::forget('move_fileName');

        Session::flash('alert-warning', 'Pemindahan file dibatalkan!');
        return redirect('/'.$bidangPrefix.'/folder/'. $urlPath);
    }

    public function destroy($bidangPrefix, $uuid){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != $bidangPrefix && Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $file = Helper::getFileByUUID($uuid);
        $file->file_status = 'trashed';
        $file->save();
        // $filePath = $file->folder->parent_path . '/' . $file->folder->folder_name .'/'. $file->file_uuid;
        // Storage::delete($filePath);

        // $file->delete();

        Session::flash('alert-success', 'File berhasil dihapus!');
        return redirect('/' . $bidangPrefix . '/folder/' . $file->folder->url_path);
    }

    public function download($bidangPrefix, $uuid){
        $file = File::where('file_uuid', $uuid)->first();

        $filePath = $file->folder->parent_path . '/' . $file->folder->folder_name .'/'. $file->file_uuid;
        $fileName = $file->file_name;

        return Storage::download($filePath, $fileName);
    }

    private function getUUID($url){
        $split = explode('/', $url);
        return end($split);
    }
}
