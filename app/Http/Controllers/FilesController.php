<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\File;
use App\Folder;

class FilesController extends Controller
{
    public function create($role_prefix, $url_path=''){
        return view('admin.files.create', ['role' => $role_prefix, 'url_path' => $url_path]);
    }

    public function store($role_prefix, $url_path='', Request $request){
        $basePath = 'public/' . $role_prefix;

        $this->validate($request,[
            'filenames' => 'required',
        ]);

        if($request->hasFile('filenames')){
            foreach ($request->file('filenames') as $file) {
                $storePath = Storage::putFile($basePath. '/'. $url_path, $file);

                $filename = $file->getClientOriginalName();
                // echo ($filename = $file->getClientOriginalName());
                $uuid = self::getUUID($storePath);
                // echo ($enc_filename = self::getUUID($path));
                $uploader = Session::get('user');

                if($url_path == ''){
                    $folder = Folder::where('parent_path', 'public')->where('folder_role', $role_prefix)->first();
                } else {
                    $folder = Folder::where('url_path', $url_path)->first();
                }
                
                File::create([
                    'uuid' => $uuid,
                    'filename' => $filename,
                    'folder_id' => $folder['id'],
                    'created_by' => Session::get('username'),
                ]);
            }
        }
        
        return redirect($role_prefix . '/folder/' .$url_path);
    }

    public function destroy($role_prefix, $uuid){
        $file = File::where('uuid', $uuid)->first();

        $filePath = $file->folder->parent_path . '/' . $file->folder->name .'/'. $file->uuid;
        Storage::delete($filePath);

        $file->delete();

        return redirect('/' . $role_prefix . '/folder/' . $file->folder->url_path);
    }

    public function download($role_prefix, $uuid){
        $file = File::where('uuid', $uuid)->first();

        $filePath = $file->folder->parent_path . '/' . $file->folder->name .'/'. $file->uuid;
        $fileName = $file->filename;

        return Storage::download($filePath, $fileName);
    }

    private function getUUID($url){
        $split = explode('/', $url);
        return end($split);
    }
}
