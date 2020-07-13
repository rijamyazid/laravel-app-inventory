<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangKspkController extends Controller
{
    public function index(){
        return view('users.bidang_kspk');
    }
}
