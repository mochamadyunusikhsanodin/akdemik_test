<?php

namespace App\Helpers;

use App\Models\Jadwal;
use App\Models\Matakuliah;
use App\Models\PresensiAkademik;
use App\Models\Krs;
use Carbon\Carbon;

class DashboardHelper
{
    /**
     * Mendapatkan tingkat kehadiran mahasiswa
     */
    public static function getAttendanceRate($nim, $kodeMk = null, $period = 'month')
    {
        $query = PresensiAkademik::where('nim', $nim);
        
        if ($kodeMk) {
            $query->where('kode_mk', $kodeMk);
        }
        
        switch ($period) {
            case 'week':
                $query->where('tanggal', '>=', Carbon::now()->startOfWeek());
                break;
            case 'month':
                $query->where('tanggal', '>=', Carbon::now()->startOfMonth());
                break;
            case 'semester':
                // Implementasi berdasarkan semester aktif
                break;
        }
        
        $total = $query->count();
        $hadir = $query->where('status_kehadiran', 'Hadir')->count();
        
        return $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
    }
    
    /**
     * Mendapatkan mata kuliah dengan tingkat kehadiran rendah
     */
    public static function getLowAttendanceCourses($threshold = 75)
    {
        return Matakuliah::whereHas('presensiAkademik')
            ->get()
            ->filter(function($matakuliah) use ($threshold) {
                $total = $matakuliah->presensiAkademik()->count();
                $hadir = $matakuliah->presensiAkademik()
                    ->where('status_kehadiran', 'Hadir')->count();
                
                $rate = $total > 0 ? ($hadir / $total) * 100 : 0;
                return $rate < $threshold;
            });
    }
    
    /**
     * Mendapatkan statistik penggunaan ruang
     */
    public static function getRoomUsageStats()
    {
        $today = Carbon::now()->locale('id')->dayName;
        
        return Jadwal::with('ruang')
            ->where('hari', $today)
            ->get()
            ->groupBy('id_ruang')
            ->map(function($jadwals, $ruangId) {
                return [
                    'ruang' => $jadwals->first()->ruang->nama_ruang ?? 'Unknown',
                    'total_jadwal' => $jadwals->count(),
                    'jadwals' => $jadwals->map(function($jadwal) {
                        return [
                            'matakuliah' => $jadwal->matakuliah->nama_mk ?? 'Unknown',
                            'waktu' => $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai
                        ];
                    })
                ];
            });
    }
    
    /**
     * Mendapatkan dosen dengan beban mengajar tertinggi
     */
    public static function getTopTeachingLoad($limit = 5)
    {
        return DB::table('pengampu')
            ->join('dosen', 'pengampu.nip', '=', 'dosen.nip')
            ->join('matakuliah', 'pengampu.kode_mk', '=', 'matakuliah.kode_mk')
            ->select('dosen.nip', 'dosen.nama', DB::raw('SUM(matakuliah.sks) as total_sks'))
            ->groupBy('dosen.nip', 'dosen.nama')
            ->orderBy('total_sks', 'desc')
            ->limit($limit)
            ->get();
    }
}