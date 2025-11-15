<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    // Dashboard dosen
    public function dashboard()
    {
        

        return view('dosen.dashboard', compact('myKrs', 'krsStatus'));
    }

    

   
}