<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Matakuliah;
use App\Models\PresensiAkademik;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Dosen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DosenController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        // Statistik untuk dashboard (sekitar line 24-36)
        $stats = [
            'total_matakuliah' => Matakuliah::whereHas('pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->count(),
            'jadwal_hari_ini' => Jadwal::whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->where('hari', Carbon::now()->locale('id')->dayName)->count(),
            'total_mahasiswa' => Krs::whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->distinct('nim')->count(),
            'presensi_hari_ini' => PresensiAkademik::whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->whereDate('tanggal', today())->count()
        ];
        
        // Jadwal hari ini (sekitar line 40)
        $jadwalHariIni = Jadwal::with(['matakuliah', 'ruang'])
            ->whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })
            
        // Query lainnya (sekitar line 58, 78, 132, 183)
        // Ganti semua $q->where('nip', $nip) menjadi $q->where('pengampu.nip', $nip)
            ->where('hari', Carbon::now()->locale('id')->dayName)
            ->orderBy('jam_mulai')
            ->get();
            
        return view('dosen.dashboard', compact('stats', 'jadwalHariIni'));
    }
    
    public function jadwal(Request $request)
    {
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        $semester_filter = $request->get('semester', '2024/2025 Ganjil');
        
        $jadwals = Jadwal::with(['matakuliah', 'ruang'])
            ->whereHas('matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip); // Tambahkan alias tabel pengampu
            })
            ->where('semester_tahun', $semester_filter)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');
            
        $semesters = ['2024/2025 Ganjil', '2024/2025 Genap', '2025/2026 Ganjil', '2025/2026 Genap'];
        
        return view('dosen.jadwal', compact('jadwals', 'semester_filter', 'semesters'));
    }
    
    public function presensi(Request $request)
    {
        // This method should be the same as the absensi method
        // or redirect to absensi method
        return $this->absensi($request);
    }
    
    public function absensi(Request $request)
    {
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        // Mata kuliah yang diampu dosen
        $matakuliahs = Matakuliah::whereHas('pengampu', function($q) use ($nip) {
            $q->where('pengampu.nip', $nip); // Tambahkan prefix tabel
        })->get();
        
        $selectedMk = $request->get('kode_mk');
        $selectedDate = $request->get('tanggal', today()->format('Y-m-d'));
        
        $mahasiswas = collect();
        $presensiData = collect();
        $matakuliah = null;
        $absensiAktif = false;
        
        if ($selectedMk) {
            $matakuliah = Matakuliah::find($selectedMk);
            $absensiAktif = $matakuliah ? $matakuliah->isAbsensiAktif() : false;
            
            if ($absensiAktif) {
                // Debug: Cek total KRS untuk mata kuliah ini
                $totalKrs = Krs::whereHas('jadwal', function($q) use ($selectedMk) {
                    $q->where('kode_mk', $selectedMk);
                })->count();
                
                \Log::info('Debug Absensi - Total KRS untuk mata kuliah ' . $selectedMk . ': ' . $totalKrs);
                
                // Debug: Cek KRS dengan status
                $krsWithStatus = Krs::with('jadwal')
                    ->whereHas('jadwal', function($q) use ($selectedMk) {
                        $q->where('kode_mk', $selectedMk);
                    })
                    ->get();
                    
                \Log::info('Debug Absensi - KRS dengan detail:', $krsWithStatus->toArray());
                
                // Ambil mahasiswa yang mengambil mata kuliah ini
                $mahasiswas = Mahasiswa::whereHas('krs', function($q) use ($selectedMk) {
                    $q->whereHas('jadwal', function($subQ) use ($selectedMk) {
                        $subQ->where('kode_mk', $selectedMk);
                    })->where('status', 'approved');
                })->get();
                
                \Log::info('Debug Absensi - Jumlah mahasiswa ditemukan: ' . $mahasiswas->count());
                \Log::info('Debug Absensi - Data mahasiswa:', $mahasiswas->pluck('nim', 'nama')->toArray());
                
                // Ambil data presensi yang sudah ada
                $presensiData = PresensiAkademik::where('kode_mk', $selectedMk)
                    ->where('tanggal', $selectedDate)
                    ->get()
                    ->keyBy('nim');
            }
        }
        
        return view('dosen.absensi', compact('matakuliahs', 'selectedMk', 'selectedDate', 'mahasiswas', 'presensiData', 'matakuliah', 'absensiAktif'));
    }
    
    // Method baru untuk mengaktifkan/menonaktifkan absensi
    public function toggleAbsensi(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk',
            'absensi_aktif' => 'required|boolean',
            'tanggal_mulai_absensi' => 'nullable|date',
            'tanggal_selesai_absensi' => 'nullable|date|after_or_equal:tanggal_mulai_absensi',
            'jam_buka_absensi' => 'nullable|date_format:H:i',
            'jam_tutup_absensi' => 'nullable|date_format:H:i|after:jam_buka_absensi',
            'toleransi_keterlambatan' => 'nullable|integer|min:0|max:60'
        ]);
        
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        // Pastikan dosen adalah pengampu mata kuliah ini
        $matakuliah = Matakuliah::whereHas('pengampu', function($q) use ($nip) {
            $q->where('pengampu.nip', $nip);
        })->where('kode_mk', $request->kode_mk)->first();
        
        if (!$matakuliah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mata kuliah ini');
        }
        
        $matakuliah->update([
            'absensi_aktif' => $request->absensi_aktif,
            'tanggal_mulai_absensi' => $request->tanggal_mulai_absensi,
            'tanggal_selesai_absensi' => $request->tanggal_selesai_absensi,
            'jam_buka_absensi' => $request->jam_buka_absensi,
            'jam_tutup_absensi' => $request->jam_tutup_absensi,
            'toleransi_keterlambatan' => $request->toleransi_keterlambatan ?? 15
        ]);
        
        return redirect()->back()->with('success', 'Pengaturan absensi berhasil diperbarui');
    }
    
    public function storeAbsensi(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk',
            'tanggal' => 'required|date',
            'presensi' => 'required|array',
            'presensi.*' => 'required|in:hadir,tidak_hadir,izin,sakit'
        ]);
        
        foreach ($request->presensi as $nim => $status) {
            PresensiAkademik::updateOrCreate(
                [
                    'nim' => $nim,
                    'kode_mk' => $request->kode_mk,
                    'tanggal' => $request->tanggal
                ],
                [
                    'status_kehadiran' => $status
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Data presensi berhasil disimpan.');
    }
    
    public function laporanAbsensi(Request $request)
    {
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        $matakuliahs = Matakuliah::whereHas('pengampu', function($q) use ($nip) {
            $q->where('pengampu.nip', $nip); // Change from 'nip' to 'pengampu.nip'
        })->get();
        
        $selectedMk = $request->get('kode_mk');
        $bulan = $request->get('bulan', now()->format('Y-m'));
        
        $laporan = collect();
        
        if ($selectedMk) {
            $laporan = PresensiAkademik::with(['mahasiswa'])
                ->where('kode_mk', $selectedMk)
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->get()
                ->groupBy('nim')
                ->map(function($presensiMahasiswa) {
                    $total = $presensiMahasiswa->count();
                    $hadir = $presensiMahasiswa->where('status_kehadiran', 'hadir')->count();
                    $tidak_hadir = $presensiMahasiswa->where('status_kehadiran', 'tidak_hadir')->count();
                    $izin = $presensiMahasiswa->where('status_kehadiran', 'izin')->count();
                    $sakit = $presensiMahasiswa->where('status_kehadiran', 'sakit')->count();
                    
                    return [
                        'mahasiswa' => $presensiMahasiswa->first()->mahasiswa,
                        'total' => $total,
                        'hadir' => $hadir,
                        'tidak_hadir' => $tidak_hadir,
                        'izin' => $izin,
                        'sakit' => $sakit,
                        'persentase_kehadiran' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0
                    ];
                });
        }
        
        return view('dosen.laporan-absensi', compact('matakuliahs', 'selectedMk', 'bulan', 'laporan'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        // Ambil data dosen dari tabel dosen
        $dosen = Dosen::where('nip', $nip)->first();
        
        // Jika data dosen belum ada, buat data default
        if (!$dosen) {
            $dosen = Dosen::create([
                'nip' => $nip,
                'nama' => $user->name,
                'alamat' => $user->location,
                'no_hp' => $user->phone
            ]);
        }
        
        // Statistik dosen
        $stats = [
            'total_matakuliah' => Matakuliah::whereHas('pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->count(),
            'total_mahasiswa' => Krs::whereHas('jadwal.matakuliah.pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->distinct('nim')->count(),
            'mata_kuliah_diampu' => Matakuliah::whereHas('pengampu', function($q) use ($nip) {
                $q->where('pengampu.nip', $nip);
            })->get()
        ];
        
        return view('dosen.profile', compact('user', 'dosen', 'stats'));
    }
    
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'location' => 'max:255',
            'phone' => 'numeric|digits_between:10,15',
            'about' => 'max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'max:500',
            'no_hp' => 'numeric|digits_between:10,15'
        ]);
        
        $user = Auth::user();
        $nip = $user->nim_nip;
        
        // Update data user
        $updateUserData = [
            'name' => $request->name,
            'email' => $request->email,
            'location' => $request->location,
            'phone' => $request->phone,
            'about' => $request->about,
        ];
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            
            $avatarFile = $request->file('avatar');
            $avatarName = time() . '_' . Str::random(10) . '.' . $avatarFile->getClientOriginalExtension();
            $avatarFile->storeAs('avatars', $avatarName, 'public');
            $updateUserData['avatar'] = $avatarName;
        }
        
        $user->update($updateUserData);
        
        // Update data dosen
        $dosen = Dosen::where('nip', $nip)->first();
        if ($dosen) {
            $dosen->update([
                'nama' => $request->name,
                'alamat' => $request->alamat ?? $request->location,
                'no_hp' => $request->no_hp ?? $request->phone
            ]);
        }
        
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}