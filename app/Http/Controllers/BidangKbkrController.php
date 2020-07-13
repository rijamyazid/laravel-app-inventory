<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangKbkrController extends Controller
{
    public function index(){
        return view('users.bidang_kbkr');
    }
}
