<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Bidang;
use App\User;
use App\Folder;
use Helper;

class BidangController extends Controller
{
    public function view(){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') {
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        Session::put('side_loc', 'kelola_bidang');

        return view('content.bidang.view',
        [
            'bidangPrefix' => 'super_admin',
            'bidangData' => Bidang::get(),
            'bidangS' => Bidang::orderBy('bidang_name', 'asc')->get()
        ]);
    }

    public function create(Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        if(is_null($request->bidang_name) || empty($request->bidang_name)){
            $request->session()->flash('alert-danger', 'Nama bidang tidak boleh kosong!');
            return redirect('/super_admin/view/bidang');
        } else if(count(Bidang::where('bidang_name', '=', $request->bidang_name)->get()) > 0){
            $request->session()->flash('alert-danger', 'Nama bidang harus unik!');
            return redirect('/super_admin/view/bidang');
        }

        $bidangName = $request->bidang_name;
        $bidangPrefix = Helper::getBidangPrefix($bidangName);

        $user = User::where('user_username', '=', Session::get('username'))->first();
        Storage::makeDirectory('public/' . $bidangPrefix);
        Bidang::create([
            'bidang_name' => $bidangName,
            'bidang_prefix' => $bidangPrefix
        ]);

        $bidang = Bidang::where('bidang_prefix', '=', $bidangPrefix)->first();
        Folder::create([
            'folder_name' => $bidangPrefix,
            'parent_path' => 'public',
            'user_id' => $user->id,
            'bidang_id' => $bidang->id
        ]);
        
        $request->session()->flash('alert-success', 'Bidang berhasil ditambahkan!');
        return redirect('/super_admin/view/bidang');
    }

    public function edit($bidangID){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        return view('content.bidang.edit',
        [
            'bidangPrefix' => 'super_admin',
            'bidang' => Bidang::find($bidangID),
            'bidangS' => Bidang::orderBy('bidang_name', 'asc')->get()
        ]);        
    }

    public function update($bidangID, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        if(is_null($request->bidang_name) || empty($request->bidang_name)){
            $request->session()->flash('alert-danger', 'Nama bidang tidak boleh kosong!');
            return redirect("/super_admin/edit/bidang/$bidangID");
        } else if(count(Bidang::where('bidang_name', '=', $request->bidang_name)->get()) > 0){
            $request->session()->flash('alert-danger', 'Nama bidang harus unik!');
            return redirect("/super_admin/edit/bidang/$bidangID");
        }

        $bidang = Bidang::find($bidangID);
        $oldBidangPrefix = $bidang->bidang_prefix;
        $newBidangPrefix = Helper::getBidangPrefix($request->bidang_name);
        $bidang->bidang_name = $request->bidang_name;
        $bidang->bidang_prefix = $newBidangPrefix;
        $bidang->save();

        $folder = Folder::where('folder_name', '=', $oldBidangPrefix)->first();
        $folder->folder_name = $newBidangPrefix;
        $folder->save();
        Storage::move("public/$oldBidangPrefix", "public/$newBidangPrefix");

        $folders = Folder::where('parent_path', 'like', 'public/'.$oldBidangPrefix.'%')->get();
        foreach($folders as $folder){
            $pos = strpos($folder->parent_path, $oldBidangPrefix);
            if ($pos !== false) {
                $newStr = substr_replace($folder->parent_path, $newBidangPrefix, $pos, strlen($oldBidangPrefix));
            }

            $folder->parent_path = $newStr;
            $folder->save();
        }

        $request->session()->flash('alert-success', 'Bidang berhasil diubah!');
        return redirect('/super_admin/view/bidang');
    }

    public function delete($bidangID){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $bidang = Bidang::find($bidangID);
        $folder = Folder::where('folder_name', '=', $bidang->bidang_prefix)->first();
        Storage::deleteDirectory($folder->parent_path .'/'. $folder->folder_name);
        
        $folders = Folder::where('parent_path', 'like', 'public/'.$bidang->bidang_prefix.'%')->get();
        foreach ($folders as $folder) {
            $folder->delete();
        }

        $folder->delete();
        $bidang->delete();

        Session::flash('alert-success', 'Bidang berhasil dihapus!');
        return redirect('/super_admin/view/bidang');
    }
}
