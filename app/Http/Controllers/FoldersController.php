<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Role;
use App\Folder;

class FoldersController extends Controller
{
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

        return redirect('/' . $role);
    }

    private function getRolePrefix($roleName){
        $split = explode(' ', strtolower($roleName));
        return implode('_', $split);
    }
}
