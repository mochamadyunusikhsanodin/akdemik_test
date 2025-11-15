<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Data statistik untuk cards
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $dosenCount = User::where('role', 'dosen')->count();
        $mahasiswaCount = User::where('role', 'mahasiswa')->count();
        
        // Data pengguna terbaru untuk tabel
        $recentUsers = User::latest()->take(10)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'adminCount', 
            'dosenCount', 
            'mahasiswaCount', 
            'recentUsers'
        ));
    }
}