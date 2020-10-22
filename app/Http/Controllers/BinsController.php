<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Helper;
use App\Bidang;
use App\Folder;
use App\File;

class BinsController extends Controller
{
    public function view($bidangPrefix, $urlPath = null){
        if(is_null(Session::get('username'))) return redirect('/');

        Session::put('side_loc', 'kelola_sampah_sementara');

        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
        
        if(is_null($urlPath)) $url_path_new = 'public/'.$bidangPrefix;
        else $url_path_new = 'public/'.$bidangPrefix.'/'.$urlPath;
        if($bidangPrefix == 'super_admin') $bidangPrefix = Bidang::where('bidang_prefix', '!=', 'super_admin')->orderBy('bidang_name', 'asc')->first()->bidang_prefix;
        $folders = Folder::where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                    ->where('folder_name','like','%_trashed')
                    ->where('folder_status','=','trashed')
                    ->orderBy('folder_name', 'asc')->get();
        
        $files = File::join('folders', 'folders.id', '=', 'files.folder_id')
            ->where('file_status','=','trashed')
            ->where('folders.bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
            ->orderBy('file_name', 'asc')->get();

        return view('content.bins.view', 
            [
                'urlPath'       => $urlPath,
                'bidangPrefix'  => $bidangPrefix,
                'bidangS'       => $bidangS,
                'folders'       => $folders, 
                'files'         => $files
            ]);
    }

    public function restoreFolder($bidangPrefix, $folderId){
        if(is_null(Session::get('username'))) return redirect('/');

        $folder = Folder::find($folderId);
        $folderActual = Folder::where('url_path', '=', $folder->url_path)
                        ->where('bidang_id', '=', $folder->bidang_id)
                        ->first();
        $folderActual->folder_status = 'available';
        $folders = Folder::where('url_path', 'like', $folderActual->url_path.'/%')
                    ->where('bidang_id', '=', $folderActual->bidang_id)
                    ->get();
        $files = File::where('folder_id', '=', $folderActual->id)->get();
        foreach ($files as $_file) {
            $_file->file_status = 'available';
            $_file->save();
        }
        foreach($folders as $_folder){
            $_folder->folder_status = 'available';
            $_files = File::where('folder_id', '=', $_folder->id)->get();
            foreach ($_files as $__file) {
                $__file->file_status = 'available';
                $__file->save();
            }
            $_folder->save();
        }
        foreach(Helper::folderLocation($folder->url_path) as $paths){
            $_foldersTrashed = Folder::where('url_path', '=', substr($paths['urlPath'], 0, -1))
                                ->where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                                ->get();
            $_foldersTrashed[0]->folder_status = 'available';
            $_foldersTrashed[0]->save();
        }
        $folderActual->save();
        $folder->delete();

        return redirect('/' . $bidangPrefix . '/bin');
    }

    public function deleteFolder($bidangPrefix, $folderId){
        if(is_null(Session::get('username'))) return redirect('/');

        $folder = Folder::find($folderId);
        $folders = Folder::where('url_path', 'like', $folder->url_path.'/%')
                    ->where('folder_status', '=', 'trashed')
                    ->where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                    ->get();
        $folderTrashed = Folder::where('url_path', '=', $folder->url_path)
                            ->where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                            ->get();
        foreach($folders as $_folder){
            // if($folderTrashed[0]->folder_status == 'trashed'){
                Storage::deleteDirectory($_folder->parent_path.'/'.$_folder->folder_name);
            // }
            $_folder->delete();
        }
        if($folderTrashed[0]->folder_status == 'trashed'){
            Storage::deleteDirectory($folderTrashed[0]->parent_path.'/'.$folderTrashed[0]->folder_name);
            $folderTrashed[0]->delete();
            $folderTrashed[1]->delete();
        } else {
            $folderTrashed[1]->delete();
        }

        return redirect('/' . $bidangPrefix . '/bin');
    }

    public function restoreFile($bidangPrefix, $uuid){
        if(is_null(Session::get('username'))) return redirect('/');

        $file = Helper::getFileByUUID($uuid);
        foreach(Helper::folderLocation($file->folder->url_path) as $paths){
            $_foldersTrashed = Folder::where('url_path', '=', substr($paths['urlPath'], 0, -1))
                                ->where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                                ->get();
            $_foldersTrashed[0]->folder_status = 'available';
            $_foldersTrashed[0]->save();
        }

        $file->file_status = 'available';
        $file->save();

        return redirect('/' . $bidangPrefix . '/bin');
    }

    public function deleteFile($bidangPrefix, $uuid){
        if(is_null(Session::get('username'))) return redirect('/');
        $file = Helper::getFileByUUID($uuid);

        Storage::delete($file->folder->parent_path.'/'.$file->folder->folder_name.'/'.$file->file_uuid);

        $file->delete();

        return redirect('/' . $bidangPrefix . '/bin');
    }
}
