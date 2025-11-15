<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class KrsLimitController extends Controller
{
    public function index()
    {
        $maxSks = Setting::getMaxSks();
        $maxMatakuliah = Setting::getMaxMatakuliah();
        
        return view('admin.krs-limit.index', compact('maxSks', 'maxMatakuliah'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'max_sks' => 'required|integer|min:1|max:30',
            'max_matakuliah' => 'required|integer|min:1|max:15'
        ]);
        
        Setting::setMaxSks($request->max_sks);
        Setting::setMaxMatakuliah($request->max_matakuliah);
        
        return redirect()->route('admin.krs-limit.index')
            ->with('success', 'Pengaturan limit KRS berhasil diperbarui!');
    }
}