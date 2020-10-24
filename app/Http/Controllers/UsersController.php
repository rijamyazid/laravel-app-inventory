<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Folder;
use App\File;
use App\Bidang;
use App\User;

class UsersController extends Controller
{

    public function index($bidangPrefix){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        }

        Session::put('side_loc', 'dashboard');

        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();

        return view('content.index', 
                    [ 
                        'roleS'         => Session::get('role'), 
                        'bidangPrefix'  => $bidangPrefix,
                        'bidangS'       => $bidangS
                    ]);
    }

    public function view(){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        Session::put('side_loc', 'kelola_user');

        $users = User::get();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();      

        return view('content.users.view', 
            [
                'users'          => $users,
                'bidangPrefix'  => 'super_admin',
                'bidangS'       => $bidangS
            ]);
    }

    public function create(){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $bidangs = Bidang::orderBy('bidang_name', 'asc')->get();

        return view('content.users.create', 
            [
                'bidangPrefix' => 'super_admin',
                'bidangS' => $bidangs
            ]);
    }

    public function store(Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        if(is_null($request->username) || empty($request->username)){
            $request->session()->flash('alert-danger', 'Masukan username!');
            return redirect("/super_admin/create/user");
        } else if(count(User::where('user_name', '=', $request->username)->get()) > 0){
            $request->session()->flash('alert-danger', 'Username sudah digunakan!');
            return redirect("/super_admin/create/user");
        } else if(is_null($request->password) || empty($request->password)){
            $request->session()->flash('alert-danger', 'Masukan username!');
            return redirect("/super_admin/create/user");
        } else if(is_null($request->name) || empty($request->name)){
            $request->session()->flash('alert-danger', 'Masukan nama');
            return redirect("/super_admin/create/user");
        } else if(is_null($request->bidang) || empty($request->bidang)){
            $request->session()->flash('alert-danger', 'Masukan bidang');
            return redirect("/super_admin/create/user");
        }

        $username = $request->username;
        $password = $request->password;
        $name = $request->name;
        $bidang = $request->bidang;

        User::create([
            'user_username' => $username,
            'user_password' => $password,
            'user_name' => $name,
            'bidang_id' => $bidang,
        ]);

        $request->session()->flash('alert-success', 'User baru sudah ditambahkan');
        return redirect('/'. Session::get('rolePrefix') .'/view/user');
    }

    public function edit($bidangPrefix, $username){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $user = User::where('user_username' , '=' , $username)->first();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
        
        return view('content.users.edit', 
            [   
                'user' => $user,
                'bidangPrefix' => 'super_admin',
                'bidangS' => $bidangS
            ]);
    }
    
    public function update($url_path , $username , Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        if(is_null($request->username) || empty($request->username)){
            $request->session()->flash('alert-danger', 'Masukan username!');
            return redirect("/super_admin/edit/user/$username");
        } else if(count(User::where('user_name', '=', $request->username)->get()) > 0){
            $request->session()->flash('alert-danger', 'Username sudah digunakan!');
            return redirect("/super_admin/edit/user/$username");
        } else if(is_null($request->password) || empty($request->password)){
            $request->session()->flash('alert-danger', 'Masukan username!');
            return redirect("/super_admin/edit/user/$username");
        } else if(is_null($request->name) || empty($request->name)){
            $request->session()->flash('alert-danger', 'Masukan nama');
            return redirect("/super_admin/edit/user/$username");
        } else if(is_null($request->bidang) || empty($request->bidang)){
            $request->session()->flash('alert-danger', 'Masukan bidang');
            return redirect("/super_admin/edit/user/$username");
        }

        $user = User::where('user_username' , '=' , $username)->first();

        $user->user_password = $request->password;
        $user->user_name = $request->name;
        $user->bidang_id = $request->role;
        $user->save();

        $request->session()->flash('alert-success', 'Data user berhasil diubah!');
        return redirect('/'. Session::get('rolePrefix') .'/view/user');
    }

    public function delete($bidangPrefix, $username){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        } else if(Session::get('rolePrefix') != 'super_admin') { 
            return redirect('/'. Session::get('rolePrefix'). '/folder');
        }

        $user = User::where('user_username' , '=' , $username)->first();
        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();

        $user->delete();

        Session::flash('alert-success', 'User berhasil dihapus');
        return redirect('/'. Session::get('rolePrefix') .'/view/user');
    }

    public function logout(){
        Session::flush();
        return redirect('/');
    }
}
