<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Krs;  // Tambahkan baris ini
use App\Models\Matakuliah;
use App\Models\PresensiAkademik;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $nim = $user->nim_nip;
        
        // Get current semester
        $currentSemester = $this->getCurrentSemester();
        
        // Debug: Check total KRS count and semester-specific count
        $krsCount = Krs::where('nim', $nim)->count();
        $krsSemesterCount = Krs::where('nim', $nim)
                              ->where('semester_tahun', $currentSemester)
                              ->count();
        
        \Log::info('Debug Dashboard', [
            'nim' => $nim,
            'currentSemester' => $currentSemester,
            'krs_count' => $krsCount,
            'krs_semester_count' => $krsSemesterCount
        ]);
        
        // Statistik untuk dashboard
        $stats = [
            'krs_diambil' => $krsSemesterCount,
            'jadwal_hari_ini' => Jadwal::whereHas('krs', function($q) use ($nim) {
                $q->where('nim', $nim)->where('status', 'approved');
            })->where('hari', Carbon::now()->locale('id')->dayName)->count(),
            'presensi_bulan_ini' => PresensiAkademik::where('nim', $nim)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->where('status_kehadiran', 'Hadir')
                ->count(),
            'total_sks_diambil' => Krs::where('krs.nim', $nim)
                                     ->where('krs.semester_tahun', $currentSemester)
                                     ->where('krs.status', 'approved')
                                     ->join('jadwals', 'krs.jadwal_id', '=', 'jadwals.id')
                                     ->join('matakuliah', 'jadwals.kode_mk', '=', 'matakuliah.kode_mk')
                                     ->sum('matakuliah.sks')
        ];
        
        // Jadwal hari ini - mengambil jadwal mahasiswa yang sudah approved
        $jadwalHariIni = Jadwal::with(['matakuliah', 'ruang'])
            ->whereHas('krs', function($q) use ($nim) {
                $q->where('nim', $nim)->where('status', 'approved');
            })
            ->where('hari', Carbon::now()->locale('id')->dayName)
            ->orderBy('jam_mulai')
            ->get();
            
        return view('mahasiswa.dashboard', compact('stats', 'jadwalHariIni'));
    }
    
    private function getCurrentSemester()
{
    // Hardcode ke semester yang ada di database untuk sementara
    return '2024/2025 Genap'; // atau '2024/2025 Ganjil' tergantung semester aktif
    
    // Atau buat logic yang lebih dinamis:
    // return '2024/2025 Genap'; // untuk semester genap 2024/2025


    $year = Carbon::now()->year;
    $month = Carbon::now()->month;
    
    // Ubah format sesuai dengan database
    if ($month >= 9 || $month <= 2) {
        $semesterYear = $month <= 2 ? $year - 1 : $year;
        return $semesterYear . '/' . ($semesterYear + 1) . ' Ganjil';
    } else {
        return $year . '/' . ($year + 1) . ' Genap';
    }
}
   public function jadwal(Request $request)
{
    $user = Auth::user();
    $nim = $user->nim_nip;
    
    // Get semester filter from request
    $semester_filter = $request->get('semester', '2024/2025 Ganjil');
    
    // Define available semesters
    $semesters = ['2024/2025 Ganjil', '2024/2025 Genap', '2025/2026 Ganjil', '2025/2026 Genap'];
    
    // Ambil jadwal melalui KRS dengan eager loading
    $krsData = Krs::where('nim', $nim)
        ->with(['jadwal.matakuliah.pengampu', 'jadwal.ruang'])
        ->whereHas('jadwal', function($q) use ($semester_filter) {
            $q->where('semester_tahun', $semester_filter);
        })
        ->get();
    
    // Jika tidak ada data KRS, kirim array kosong
    if ($krsData->isEmpty()) {
        $jadwals = collect();
        return view('mahasiswa.jadwal', compact('jadwals', 'semesters', 'semester_filter'));
    }
    
    // Group KRS data by hari (day) instead of extracting jadwal
    $jadwals = $krsData->filter(function($krs) {
        // Only include KRS with complete jadwal and matakuliah data
        return $krs->jadwal && $krs->jadwal->matakuliah && $krs->jadwal->ruang;
    })->groupBy(function($krs) {
        return $krs->jadwal->hari;
    });
    
    return view('mahasiswa.jadwal', compact('jadwals', 'semesters', 'semester_filter'));
}

    public function absensi(Request $request)
    {
        $user = Auth::user();
        $nim = $user->nim_nip;
        
        $kode_mk = $request->get('kode_mk');
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        // Semua mata kuliah
        $matakuliahs = Matakuliah::all();
        
        // Attendance history
        $absensi = PresensiAkademik::with(['matakuliah'])
            ->where('nim', $nim)
            ->when($kode_mk, function($q) use ($kode_mk) {
                return $q->where('kode_mk', $kode_mk);
            })
            ->when($tanggal, function($q) use ($tanggal) {
                return $q->whereDate('tanggal', $tanggal);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        
        return view('mahasiswa.absensi', compact('absensi', 'matakuliahs', 'kode_mk', 'tanggal'));
    }

    public function laporanAbsensi(Request $request)
    {
        $user = Auth::user();
        $nim = $user->nim_nip;
        
        $kode_mk = $request->get('kode_mk');
        $bulan = $request->get('bulan', date('Y-m'));
        
        // Semua mata kuliah
        $matakuliahs = Matakuliah::all();
        
        // Laporan absensi per mata kuliah
        $laporan = [];
        
        if ($kode_mk) {
            $totalPertemuan = PresensiAkademik::where('nim', $nim)
                ->where('kode_mk', $kode_mk)
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->count();
                
            $hadir = PresensiAkademik::where('nim', $nim)
                ->where('kode_mk', $kode_mk)
                ->where('status_kehadiran', 'Hadir')
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->count();
                
            $laporan = [
                'total_pertemuan' => $totalPertemuan,
                'hadir' => $hadir,
                'tidak_hadir' => $totalPertemuan - $hadir,
                'persentase' => $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100, 2) : 0
            ];
        }
        
        return view('mahasiswa.laporan-absensi', compact('laporan', 'matakuliahs', 'kode_mk', 'bulan'));
    }

    // Tampilkan jadwal yang tersedia untuk diambil mahasiswa
    public function krs()
    {
        $user = Auth::user();
        $nim = $user->nim_nip;
        $currentSemester = $this->getCurrentSemester();
        
        $krsData = Krs::with(['jadwal.matakuliah', 'jadwal.ruang'])
            ->where('nim', $nim)
            ->where('semester_tahun', $currentSemester)
            ->get();
            
        // Hitung total SKS dan mata kuliah saat ini
        $totalSks = $krsData->sum(function($krs) {
            return $krs->jadwal->matakuliah->sks ?? 0;
        });
        $totalMatakuliah = $krsData->count();
        
        // Ambil limit dari setting
        $maxSks = Setting::getMaxSks();
        $maxMatakuliah = Setting::getMaxMatakuliah();
        
        return view('mahasiswa.krs', compact(
            'krsData', 
            'totalSks', 
            'totalMatakuliah', 
            'maxSks', 
            'maxMatakuliah'
        ));
    }
    
    public function jadwalTersedia()
    {
        $user = Auth::user();
        $nim = $user->nim_nip;
        $currentSemester = $this->getCurrentSemester();
        
        // Ambil jadwal yang tersedia
        $jadwals = Jadwal::with(['matakuliah', 'ruang', 'matakuliah.pengampu'])
            ->where('semester_tahun', $currentSemester)
            ->get();
        
        // Hitung KRS yang sudah diambil
        $takenKrs = Krs::where('nim', $nim)
            ->where('semester_tahun', $currentSemester)
            ->with('jadwal.matakuliah')
            ->get();
        
        $totalSks = $takenKrs->sum(function($krs) {
            return $krs->jadwal->matakuliah->sks ?? 0;
        });
        $totalMatakuliah = $takenKrs->count();
        
        // Ambil limit dari setting
        $maxSks = Setting::getMaxSks();
        $maxMatakuliah = Setting::getMaxMatakuliah();
        
        return view('mahasiswa.jadwal-tersedia', compact(
            'jadwals', 
            'takenKrs',
            'totalSks', 
            'totalMatakuliah', 
            'maxSks', 
            'maxMatakuliah'
        ));
    }
    
    
    
    // Method untuk mengambil jadwal (jika diperlukan untuk sistem pendaftaran)
    public function takeSchedule(Request $request, Jadwal $jadwal)
    {
        $user = Auth::user();
        $nim = $user->nim_nip;
        
        // Cek apakah mahasiswa sudah mengambil jadwal ini
        $existingKrs = Krs::where('nim', $nim)
            ->where('jadwal_id', $jadwal->id)
            ->first();
            
        if ($existingKrs) {
            return back()->with('error', 'Anda sudah mengambil mata kuliah ini!');
        }
        
        // Validasi apakah mahasiswa sudah mengambil jadwal yang bentrok
        $konflikJadwal = Krs::where('nim', $nim)
            ->where('semester_tahun', $jadwal->semester_tahun)
            ->whereHas('jadwal', function($query) use ($jadwal) {
                $query->where('hari', $jadwal->hari)
                    ->where(function ($q) use ($jadwal) {
                        $q->whereBetween('jam_mulai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                          ->orWhereBetween('jam_selesai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                          ->orWhere(function ($subQ) use ($jadwal) {
                              $subQ->where('jam_mulai', '<=', $jadwal->jam_mulai)
                                ->where('jam_selesai', '>=', $jadwal->jam_selesai);
                          });
                    });
            })
            ->exists();
            
        if ($konflikJadwal) {
            return back()->with('error', 'Jadwal bentrok dengan mata kuliah lain yang sudah Anda ambil!');
        }
        
        // Simpan KRS
        Krs::create([
            'nim' => $nim,
            'jadwal_id' => $jadwal->id,
            'semester_tahun' => $jadwal->semester_tahun,
            'status' => 'approved'
        ]);
        
        return back()->with('success', 'Jadwal berhasil diambil! Mata kuliah ' . $jadwal->matakuliah->nama_mk . ' telah ditambahkan ke KRS Anda.');
    }

    public function addKrs(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id'
        ]);
    
        $user = Auth::user();
        $nim = $user->nim_nip;
        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        
        // Cek apakah mahasiswa sudah mengambil jadwal ini
        $existingKrs = Krs::where('nim', $nim)
            ->where('jadwal_id', $jadwal->id)
            ->first();
            
        if ($existingKrs) {
            return redirect()->route('mahasiswa.jadwal.tersedia')->with('error', 'Anda sudah mengambil mata kuliah ini!');
        }
        
        // Cek limit KRS (SKS dan jumlah mata kuliah)
        $newSks = $jadwal->matakuliah->sks ?? 0;
        $limitCheck = Setting::checkKrsLimit($nim, $jadwal->semester_tahun, $newSks);
        
        if (!$limitCheck['can_add']) {
            $errorMessage = '';
            
            if ($limitCheck['new_total_sks'] > $limitCheck['max_sks']) {
                $errorMessage = "Tidak dapat menambah mata kuliah. Total SKS akan melebihi batas maksimal ({$limitCheck['max_sks']} SKS). Saat ini: {$limitCheck['current_sks']} SKS, akan menjadi: {$limitCheck['new_total_sks']} SKS.";
            } elseif ($limitCheck['current_matakuliah'] >= $limitCheck['max_matakuliah']) {
                $errorMessage = "Tidak dapat menambah mata kuliah. Anda sudah mencapai batas maksimal {$limitCheck['max_matakuliah']} mata kuliah per semester.";
            }
            
            return redirect()->route('mahasiswa.jadwal.tersedia')->with('error', $errorMessage);
        }
        
        // Cek konflik jadwal
        $konflikJadwal = Krs::where('nim', $nim)
            ->whereHas('jadwal', function($q) use ($jadwal) {
                $q->where('hari', $jadwal->hari)
                  ->where(function($query) use ($jadwal) {
                      $query->whereBetween('jam_mulai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                            ->orWhereBetween('jam_selesai', [$jadwal->jam_mulai, $jadwal->jam_selesai])
                            ->orWhere(function($q) use ($jadwal) {
                                $q->where('jam_mulai', '<=', $jadwal->jam_mulai)
                                  ->where('jam_selesai', '>=', $jadwal->jam_selesai);
                            });
                  });
            })
            ->exists();
            
        if ($konflikJadwal) {
            return redirect()->route('mahasiswa.jadwal.tersedia')->with('error', 'Jadwal bentrok dengan mata kuliah lain yang sudah Anda ambil!');
        }
        
        // Simpan KRS
        Krs::create([
            'nim' => $nim,
            'jadwal_id' => $jadwal->id,
            'semester_tahun' => $jadwal->semester_tahun,
            'status' => 'approved'
        ]);
        
        $successMessage = "Jadwal berhasil diambil! Mata kuliah {$jadwal->matakuliah->nama_mk} ({$newSks} SKS) telah ditambahkan ke KRS Anda. ";
        $successMessage .= "Total SKS: {$limitCheck['new_total_sks']}/{$limitCheck['max_sks']}, ";
        $successMessage .= "Total mata kuliah: " . ($limitCheck['current_matakuliah'] + 1) . "/{$limitCheck['max_matakuliah']}";
        
        return redirect()->route('mahasiswa.jadwal.tersedia')->with('success', $successMessage);
    }

public function storeAbsensi(Request $request)
{
    $request->validate([
        'kode_mk' => 'required|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric'
    ]);

    // Add your attendance storage logic here
    // For example:
    // - Check if student is enrolled in the course
    // - Verify location is within allowed range
    // - Store attendance record
    
    return response()->json([
        'success' => true,
        'message' => 'Absensi berhasil disimpan'
    ]);
}
}
