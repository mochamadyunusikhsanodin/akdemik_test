<?php

namespace App\Http\Controllers;

use App\Models\Krs;
use App\Models\KrsMahasiswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    // Tampilkan KRS yang tersedia
    public function krs()
    {
        if (!Setting::isKrsOpen()) {
            return redirect()->back()
                ->with('error', 'Periode pengambilan KRS sedang ditutup');
        }

        $availableKrs = Krs::where('status', 'tersedia')
            ->with('mataKuliah')
            ->get();
            
        $myKrs = KrsMahasiswa::where('mahasiswa_id', Auth::id())
            ->with(['krs.mataKuliah'])
            ->get();

        return view('mahasiswa.krs', compact('availableKrs', 'myKrs'));
    }

    // Ambil KRS
    public function storeKrs(Request $request)
    {
        if (!Setting::isKrsOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Periode pengambilan KRS sedang ditutup'
            ]);
        }

        $request->validate([
            'krs_id' => 'required|exists:krs,id'
        ]);

        $krs = Krs::findOrFail($request->krs_id);
        
        // Cek apakah sudah mengambil KRS ini
        $existing = KrsMahasiswa::where('mahasiswa_id', Auth::id())
            ->where('krs_id', $request->krs_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengambil mata kuliah ini'
            ]);
        }

        // Cek kuota
        $currentCount = KrsMahasiswa::where('krs_id', $request->krs_id)->count();
        if ($currentCount >= $krs->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota mata kuliah sudah penuh'
            ]);
        }

        KrsMahasiswa::create([
            'mahasiswa_id' => Auth::id(),
            'krs_id' => $request->krs_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KRS berhasil diambil'
        ]);
    }

    // Batalkan KRS
    public function cancelKrs($id)
    {
        if (!Setting::isKrsOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Periode pengambilan KRS sedang ditutup'
            ]);
        }

        $krsMahasiswa = KrsMahasiswa::where('mahasiswa_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$krsMahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'KRS tidak ditemukan'
            ]);
        }

        $krsMahasiswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'KRS berhasil dibatalkan'
        ]);
    }
}