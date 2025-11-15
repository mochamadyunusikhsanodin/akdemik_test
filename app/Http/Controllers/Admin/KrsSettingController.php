<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class KrsSettingController extends Controller
{
    public function index()
    {
        $krsStatus = Setting::get('krs_registration_open', '0');
        return view('admin.krs.settings', compact('krsStatus'));
    }

    public function toggle(Request $request)
    {
        $currentStatus = Setting::get('krs_registration_open', '0');
        $newStatus = $currentStatus === '1' ? '0' : '1';
        
        Setting::set(
            'krs_registration_open', 
            $newStatus, 
            'Status pendaftaran KRS (0=Tutup, 1=Buka)'
        );

        $message = $newStatus === '1' 
            ? 'Pendaftaran KRS berhasil dibuka. Mahasiswa sekarang dapat mengakses menu KRS.' 
            : 'Pendaftaran KRS berhasil ditutup. Menu KRS tidak akan muncul untuk mahasiswa.';

        return redirect()->back()->with('success', $message);
    }
}