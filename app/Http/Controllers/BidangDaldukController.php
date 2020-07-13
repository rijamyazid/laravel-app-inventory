<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangDaldukController extends Controller
{
    public function index(){
        return view('users.bidang_dalduk');
    }
}
