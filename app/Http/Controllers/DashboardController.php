<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Jadwal;
use App\Models\Krs;
use App\Models\Matakuliah;
use App\Models\PresensiAkademik;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik umum sistem
        $stats = $this->getSystemStats();
        
        // Statistik semester ini
        $currentSemester = $this->getCurrentSemester();
        $semesterStats = $this->getSemesterStats($currentSemester);
        
        // Data dashboard berdasarkan role
        $dashboardData = $this->getDashboardDataByRole($user, $currentSemester);
        
        // Jadwal hari ini
        $jadwalHariIni = $this->getJadwalHariIni();
        
        // Aktivitas terkini
        $aktivitasTerkini = $this->getAktivitasTerkini();
        
        // Data untuk chart
        $chartData = $this->getPresensiChartData();
        
        // Mata kuliah dengan absensi aktif
        $matakuliahAktif = $this->getMatakuliahAktif();
        
        // KRS terbaru
        $krsTerbaru = $this->getKrsTerbaru();
        
        // Statistik presensi real-time
        $presensiStats = $this->getPresensiStats();
        
        // Notifikasi untuk user
        $notifications = $this->getNotifications($user);
        
        return view('dashboard', compact(
            'stats',
            'semesterStats', 
            'dashboardData',
            'jadwalHariIni',
            'aktivitasTerkini',
            'chartData',
            'matakuliahAktif',
            'krsTerbaru',
            'presensiStats',
            'notifications',
            'currentSemester'
        ));
    }
    
    private function getCurrentSemester()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        
        // Semester Ganjil: September - Februari
        // Semester Genap: Maret - Agustus
        if ($month >= 9 || $month <= 2) {
            $semesterYear = $month <= 2 ? $year - 1 : $year;
            return $semesterYear . '/' . ($semesterYear + 1) . ' Ganjil';
        } else {
            return $year . '/' . ($year + 1) . ' Genap';
        }
    }
    
    private function getPresensiChartData()
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('id')->format('M Y');
            
            $count = PresensiAkademik::whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->where('status_kehadiran', 'Hadir')
                ->count();
                
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
    
    /**
     * Mendapatkan statistik sistem secara keseluruhan
     */
    private function getSystemStats()
    {
        return [
            'total_users' => User::count(),
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_dosen' => User::where('role', 'dosen')->count(),
            'total_admin' => User::where('role', 'admin')->count(),
            'total_matakuliah' => Matakuliah::count(),
            'total_jadwal' => Jadwal::count(),
            'total_ruang' => Ruang::count(),
            'total_krs_aktif' => Krs::where('status', 'aktif')->count(),
            'matakuliah_absensi_aktif' => Matakuliah::where('absensi_aktif', true)->count(),
        ];
    }
    
    /**
     * Mendapatkan statistik semester
     */
    private function getSemesterStats($currentSemester)
    {
        return [
            'jadwal_semester_ini' => Jadwal::where('semester_tahun', $currentSemester)->count(),
            'krs_semester_ini' => Krs::where('semester_tahun', $currentSemester)->count(),
            'krs_approved' => Krs::where('semester_tahun', $currentSemester)
                                ->where('status', 'approved')->count(),
            'krs_pending' => Krs::where('semester_tahun', $currentSemester)
                               ->where('status', 'pending')->count(),
            'presensi_hari_ini' => PresensiAkademik::whereDate('tanggal', Carbon::today())->count(),
            'presensi_hadir_hari_ini' => PresensiAkademik::whereDate('tanggal', Carbon::today())
                                                        ->where('status_kehadiran', 'Hadir')->count(),
        ];
    }
    
    /**
     * Mendapatkan data dashboard berdasarkan role user
     */
    private function getDashboardDataByRole($user, $currentSemester)
    {
        $data = [];
        
        if ($user->isAdmin()) {
            $data = $this->getAdminDashboardData($currentSemester);
        } elseif ($user->isDosen()) {
            $data = $this->getDosenDashboardData($user, $currentSemester);
        } elseif ($user->isMahasiswa()) {
            $data = $this->getMahasiswaDashboardData($user, $currentSemester);
        }
        
        return $data;
    }
    
    /**
     * Data dashboard khusus admin
     */
    private function getAdminDashboardData($currentSemester)
    {
        return [
            'pending_krs' => Krs::where('status', 'pending')->count(),
            'users_registered_today' => User::whereDate('created_at', Carbon::today())->count(),
            'matakuliah_tanpa_jadwal' => Matakuliah::whereDoesntHave('jadwals')->count(),
            'ruang_terpakai_hari_ini' => Jadwal::where('hari', Carbon::now()->locale('id')->dayName)
                                              ->distinct('id_ruang')->count('id_ruang'),
            'dosen_mengajar_hari_ini' => DB::table('jadwals')
                                          ->join('matakuliah', 'jadwals.kode_mk', '=', 'matakuliah.kode_mk')
                                          ->join('pengampu', 'matakuliah.kode_mk', '=', 'pengampu.kode_mk')
                                          ->where('jadwals.hari', Carbon::now()->locale('id')->dayName)
                                          ->distinct('pengampu.nip')
                                          ->count('pengampu.nip'),
        ];
    }
    
    /**
     * Data dashboard khusus dosen
     */
    private function getDosenDashboardData($user, $currentSemester)
    {
        $nip = $user->nim_nip;
        
        return [
            'matakuliah_diampu' => Matakuliah::whereHas('pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan alias tabel
            })->count(),
            'jadwal_hari_ini' => Jadwal::whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan alias tabel
            })->where('hari', Carbon::now()->locale('id')->dayName)->count(),
            'total_mahasiswa_diampu' => Krs::whereHas('jadwal.matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan alias tabel
            })->where('status', 'approved')->distinct('nim')->count('nim'),
            'presensi_hari_ini' => PresensiAkademik::whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan alias tabel
            })->whereDate('tanggal', Carbon::today())->count(),
            'matakuliah_absensi_aktif' => Matakuliah::whereHas('pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan alias tabel
            })->where('absensi_aktif', true)->count(),
        ];
    }
    
    /**
     * Data dashboard khusus mahasiswa
     */
    private function getMahasiswaDashboardData($user, $currentSemester)
    {
        $nim = $user->nim_nip;
        
        return [
            'krs_diambil' => Krs::where('nim', $nim)
                               ->where('semester_tahun', $currentSemester)
                               ->count(),
            'krs_approved' => Krs::where('nim', $nim)
                                ->where('semester_tahun', $currentSemester)
                                ->where('status', 'approved')
                                ->count(),
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
                                     ->sum('matakuliah.sks'),
        ];
    }
    
    /**
     * Mendapatkan jadwal hari ini
     */
    private function getJadwalHariIni()
    {
        $today = Carbon::now()->locale('id')->dayName;
        return Jadwal::with(['matakuliah', 'ruang'])
            ->where('hari', $today)
            ->orderBy('jam_mulai')
            ->take(10)
            ->get();
    }
    
    /**
     * Mendapatkan mata kuliah dengan absensi aktif
     */
    private function getMatakuliahAktif()
    {
        return Matakuliah::where('absensi_aktif', true)
            ->with(['jadwals.ruang', 'pengampu'])
            ->take(8)
            ->get();
    }
    
    /**
     * Mendapatkan KRS terbaru
     */
    private function getKrsTerbaru()
    {
        return Krs::with(['mahasiswa', 'jadwal.matakuliah', 'jadwal.ruang'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }
    
    /**
     * Mendapatkan statistik presensi real-time
     */
    private function getPresensiStats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'hari_ini' => [
                'total' => PresensiAkademik::whereDate('tanggal', $today)->count(),
                'hadir' => PresensiAkademik::whereDate('tanggal', $today)
                                         ->where('status_kehadiran', 'Hadir')->count(),
                'tidak_hadir' => PresensiAkademik::whereDate('tanggal', $today)
                                                ->where('status_kehadiran', '!=', 'Hadir')->count(),
            ],
            'minggu_ini' => [
                'total' => PresensiAkademik::where('tanggal', '>=', $thisWeek)->count(),
                'hadir' => PresensiAkademik::where('tanggal', '>=', $thisWeek)
                                         ->where('status_kehadiran', 'Hadir')->count(),
            ],
            'bulan_ini' => [
                'total' => PresensiAkademik::where('tanggal', '>=', $thisMonth)->count(),
                'hadir' => PresensiAkademik::where('tanggal', '>=', $thisMonth)
                                         ->where('status_kehadiran', 'Hadir')->count(),
            ],
        ];
    }
    
    /**
     * Mendapatkan notifikasi untuk user
     */
    private function getNotifications($user)
    {
        $notifications = [];
        
        if ($user->isAdmin()) {
            // Notifikasi untuk admin
            $pendingKrs = Krs::where('status', 'pending')->count();
            if ($pendingKrs > 0) {
                $notifications[] = [
                    'type' => 'warning',
                    'title' => 'KRS Pending',
                    'message' => "Ada {$pendingKrs} KRS yang menunggu persetujuan",
                    'url' => route('admin.krs.index')
                ];
            }
        } elseif ($user->isDosen()) {
            // Notifikasi untuk dosen
            $nip = $user->nim_nip;
                       $jadwalHariIni = Jadwal::whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan prefix tabel
            })->where('hari', Carbon::now()->locale('id')->dayName)->count();
            
            if ($jadwalHariIni > 0) {
                $notifications[] = [
                    'type' => 'info',
                    'title' => 'Jadwal Mengajar',
                    'message' => "Anda memiliki {$jadwalHariIni} jadwal mengajar hari ini",
                    'url' => route('dosen.jadwal')
                ];
            }
        } elseif ($user->isMahasiswa()) {
            // Notifikasi untuk mahasiswa
            $nim = $user->nim_nip;
            $krsPending = Krs::where('nim', $nim)->where('status', 'pending')->count();
            
            if ($krsPending > 0) {
                $notifications[] = [
                    'type' => 'warning',
                    'title' => 'KRS Pending',
                    'message' => "Anda memiliki {$krsPending} KRS yang belum disetujui",
                    'url' => route('mahasiswa.krs')
                ];
            }
        }
        
        return $notifications;
    }
    
    // API untuk mendapatkan statistik real-time
    public function getStats()
    {
        $user = Auth::user();
        $stats = $this->getSystemStats();
        $presensiStats = $this->getPresensiStats();
        
        return response()->json([
            'system' => $stats,
            'presensi' => $presensiStats,
            'role_specific' => $this->getDashboardDataByRole($user, $this->getCurrentSemester())
        ]);
    }
    
    /**
     * API untuk mendapatkan aktivitas terkini dengan pagination
     */
    public function getRecentActivities(Request $request)
    {
        $limit = $request->get('limit', 20);
        $page = $request->get('page', 1);
        
        $activities = PresensiAkademik::with(['mahasiswa', 'matakuliah'])
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page)
            ->through(function($presensi) {
                return [
                    'id' => $presensi->id,
                    'type' => 'presensi',
                    'description' => ($presensi->mahasiswa->nama ?? 'Mahasiswa') . ' melakukan presensi ' . ($presensi->matakuliah->nama_mk ?? 'mata kuliah'),
                    'user' => $presensi->mahasiswa->nama ?? 'Unknown',
                    'nim' => $presensi->nim,
                    'matakuliah' => $presensi->matakuliah->nama_mk ?? 'Unknown',
                    'time' => $presensi->created_at->diffForHumans(),
                    'status' => $presensi->status_kehadiran,
                    'icon' => 'fas fa-user-check',
                    'color' => $presensi->status_kehadiran === 'Hadir' ? 'success' : 'warning'
                ];
            });
            
        return response()->json($activities);
    }
    
    /**
     * API untuk mendapatkan data chart presensi dengan filter
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'monthly'); // monthly, weekly, daily
        $period = $request->get('period', 6); // jumlah periode
        
        switch ($type) {
            case 'weekly':
                return $this->getWeeklyChartData($period);
            case 'daily':
                return $this->getDailyChartData($period);
            default:
                return $this->getPresensiChartData($period);
        }
    }
    
    /**
     * Data chart mingguan
     */
    private function getWeeklyChartData($weeks = 4)
    {
        $labels = [];
        $data = [];
        
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $startWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $labels[] = $startWeek->format('d M') . ' - ' . $endWeek->format('d M');
            
            $count = PresensiAkademik::whereBetween('tanggal', [$startWeek, $endWeek])
                ->where('status_kehadiran', 'Hadir')
                ->count();
                
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Data chart harian
     */
    private function getDailyChartData($days = 7)
    {
        $labels = [];
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->locale('id')->format('d M');
            
            $count = PresensiAkademik::whereDate('tanggal', $date)
                ->where('status_kehadiran', 'Hadir')
                ->count();
                
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Mendapatkan aktivitas terkini
     */
    private function getAktivitasTerkini()
    {
        return PresensiAkademik::with(['mahasiswa', 'matakuliah'])
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($presensi) {
                return [
                    'id' => $presensi->id,
                    'type' => 'presensi',
                    'description' => ($presensi->mahasiswa->nama ?? 'Mahasiswa') . ' melakukan presensi ' . ($presensi->matakuliah->nama_mk ?? 'mata kuliah'),
                    'user' => $presensi->mahasiswa->nama ?? 'Unknown',
                    'nim' => $presensi->nim,
                    'matakuliah' => $presensi->matakuliah->nama_mk ?? 'Unknown',
                    'time' => $presensi->created_at->diffForHumans(),
                    'status' => $presensi->status_kehadiran,
                    'icon' => 'fas fa-user-check',
                    'color' => $presensi->status_kehadiran === 'Hadir' ? 'success' : 'warning'
                ];
            });
    }
}