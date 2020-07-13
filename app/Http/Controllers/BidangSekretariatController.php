<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BidangSekretariatController extends Controller
{
    public function index(){
        return view('users.bidang_sekretariat');
    }
}
