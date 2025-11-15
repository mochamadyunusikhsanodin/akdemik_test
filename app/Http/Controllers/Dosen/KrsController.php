<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use App\Models\KrsPeriode;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        // Mata kuliah yang diampu dosen
        $matakuliahs = Matakuliah::whereHas('pengampu', function($q) use ($nip) {
            $q->where('pengampu.nip', $nip);
        })->get();
        
        $activePeriode = KrsPeriode::getActive();
        $krsStatus = Setting::getKrsStatus();
        
        return view('dosen.krs.index', compact('matakuliahs', 'activePeriode', 'krsStatus'));
    }
    
    public function toggleKrsStatus(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk',
            'krs_aktif' => 'required|boolean'
        ]);
        
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        $matakuliah = Matakuliah::whereHas('pengampu', function($q) use ($nip) {
            $q->where('pengampu.nip', $nip);
        })->where('kode_mk', $request->kode_mk)->first();
        
        if (!$matakuliah) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mata kuliah ini.']);
        }
        
        $matakuliah->update([
            'krs_aktif' => $request->krs_aktif
        ]);
        
        $status = $request->krs_aktif ? 'dibuka' : 'ditutup';
        return back()->with('success', "KRS mata kuliah {$matakuliah->nama_mk} berhasil {$status}.");
    }
}