<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Log;
use App\Bidang;
use Helper;

class LogsController extends Controller
{
    public function view($bidangPrefix){
        if(is_null(Session::get('username'))) return redirect('/');
        
        Session::put('side_loc', 'tampilkan_log');

        if($bidangPrefix == 'super_admin') $bidangPrefix = Bidang::where('bidang_prefix', '!=', 'super_admin')->orderBy('bidang_name', 'asc')->first()->bidang_prefix;
        $logs = Log::where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                ->orderBy('logs.id', 'desc')->get();
        
        return view('content.logs.view', 
                [
                    'bidangPrefix' => $bidangPrefix,
                    'bidangS' => Bidang::orderBy('bidang_name', 'asc')->get(),
                    'logs' => $logs
                ]);
    }

    public function search($bidangPrefix, Request $request){
        if(is_null(Session::get('username'))) {
            return redirect('/');
        }

        $this->validate($request,[
            'q' => 'required',
        ]);

        $bidangS = Bidang::orderBy('bidang_name', 'asc')->get();
        if($bidangPrefix == 'super_admin') $bidangPrefix = Bidang::where('bidang_prefix', '!=', 'super_admin')->orderBy('bidang_name', 'asc')->first()->bidang_prefix;
        $logs = Log::where('log_type', 'like', '%'.$request->q.'%')
                ->where('bidang_id', '=', Helper::getBidangByPrefix($bidangPrefix)->id)
                ->orderBy('logs.id', 'desc')->get();

        return view('content.logs.view', 
            [
                'bidangPrefix'  => $bidangPrefix,
                'bidangS'       => Bidang::orderBy('bidang_name', 'asc')->get(),
                'logs'          => $logs
            ]);
    }

}
