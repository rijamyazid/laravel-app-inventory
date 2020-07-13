<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangLatbangController extends Controller
{
    public function index(){
        return view('users.bidang_latbang');
    }
}
