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
        $basePath = 'public/' . $bidangPrefix;
        $fileFlag = 'public';

        if(!$request->hasFile('file_name')) throw ValidationException::withMessages(['file_name' => 'Tambahkan minimal 1 file']);
        if($request->file_flag == 'pilih' && is_null($request->file_flag_bidang)) throw ValidationException::withMessages(['file_flag_bidang' => 'Pilih minimal satu bidang untuk diberi hak akses']);
    
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
        
        Alert::success('File Berhasil Ditambah!');
        return redirect($bidangPrefix . '/folder/' .$url_path);
    }

    public function edit($bidangPrefix, $uuid){
        if(is_null(Session::get('username'))) return redirect('/');
        
        return view('content.files.edit', 
            [
                'file'         => Helper::getFileByUUID($uuid),
                'flags'        => Helper::getFlags(Helper::getFileByUUID($uuid)->file_flag),
                'bidangS'      => Bidang::orderBy('bidang_name', 'asc')->get(), 
                'bidangPrefix' => $bidangPrefix
            ]);
    }

    public function update($bidangPrefix, $uuid, Request $request){
        if(is_null(Session::get('username'))) return redirect('/');

        $file = Helper::getFileByUUID($uuid);
        if($request->file_flag == 'pilih' && is_null($request->file_flag_bidang)) throw ValidationException::withMessages(['file_flag_bidang' => 'Pilih minimal satu bidang untuk diberi hak akses']);

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
        $file->user_id = Session::get('username');
        $file->file_flag = $fileFlag;
        $file->save();

        return redirect('/'. $bidangPrefix .'/folder/'. $file->folder->url_path);
    }

    public function move($bidangPrefix, $uuid) {
        if(is_null(Session::get('username'))) return redirect('/');

        Session::put('move_uuid', $uuid);
        $urlPath = Helper::deleteUrlPathLast(Helper::getFileByUUID($uuid)->folder->url_path);
        return redirect('/'.$bidangPrefix.'/folder/'.$urlPath);
    }

    public function moving($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) return redirect('/');
        
        $file = Helper::getFileByUUID(Session::get('move_uuid'));
        $folder = Helper::getFolderByUrl($urlPath, $bidangPrefix);
        Storage::move($file->folder->parent_path .'/'. $file->folder->folder_name.'/'. $file->file_uuid, $folder->parent_path .'/'. $folder->folder_name .'/'. $file->file_uuid);
        $file->folder_id = $folder->id;
        $file->save();

        Session::forget('move_uuid');
        return redirect('/'.$bidangPrefix.'/folder/'. $urlPath);
    }

    public function destroy($bidangPrefix, $uuid){
        if(is_null(Session::get('username'))) return redirect('/');

        $file = Helper::getFileByUUID($uuid);
        $file->file_status = 'trashed';
        $file->save();
        // $filePath = $file->folder->parent_path . '/' . $file->folder->folder_name .'/'. $file->file_uuid;
        // Storage::delete($filePath);

        // $file->delete();

        Alert::warning('File Berhasil Dihapus!');
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
