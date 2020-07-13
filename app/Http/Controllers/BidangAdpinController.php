<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangAdpinController extends Controller
{
    public function index(){
        return view('users.bidang_adpin');
    }
}
