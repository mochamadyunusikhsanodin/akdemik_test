<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Matakuliah;
use App\Models\Ruang;
use App\Models\Dosen;
use App\Models\Krs; // Add this line
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $semester_filter = $request->get('semester', '2024/2025 Ganjil');
        $search = $request->get('search');
        
        $jadwals = Jadwal::with(['matakuliah', 'ruang'])
            ->when($semester_filter, function ($query, $semester_filter) {
                return $query->where('semester_tahun', $semester_filter);
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('matakuliah', function ($q) use ($search) {
                    $q->where('nama_mk', 'like', "%{$search}%")
                      ->orWhere('kode_mk', 'like', "%{$search}%");
                })->orWhereHas('ruang', function ($q) use ($search) {
                    $q->where('nama_ruang', 'like', "%{$search}%")
                      ->orWhere('id_ruang', 'like', "%{$search}%");
                })->orWhere('hari', 'like', "%{$search}%");
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(15);

        $semesters = ['2024/2025 Ganjil', '2024/2025 Genap', '2025/2026 Ganjil', '2025/2026 Genap'];

        return view('admin.jadwal.index', compact('jadwals', 'search', 'semester_filter', 'semesters'));
    }

    public function create()
    {
        $matakuliah = Matakuliah::orderBy('semester')->orderBy('kode_mk')->get();
        $ruang = Ruang::orderBy('id_ruang')->get();
        $dosen = Dosen::orderBy('nama')->get(); // Tambah data dosen
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $semesters = ['2024/2025 Ganjil', '2024/2025 Genap', '2025/2026 Ganjil', '2025/2026 Genap'];
        
        return view('admin.jadwal.create', compact('matakuliah', 'ruang', 'dosen', 'hari', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'nip' => 'required|exists:dosen,nip',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'semester_tahun' => 'required|string|max:20'
        ]);

        // Cek konflik jadwal ruang
        $konflik = Jadwal::where('id_ruang', $request->id_ruang)
            ->where('hari', $request->hari)
            ->where('semester_tahun', $request->semester_tahun)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($konflik) {
            return back()->withErrors(['error' => 'Ruang sudah digunakan pada waktu tersebut!'])->withInput();
        }

        // Cek konflik jadwal dosen
        $konflikDosen = Jadwal::join('matakuliah', 'jadwals.kode_mk', '=', 'matakuliah.kode_mk')
            ->join('pengampu', 'matakuliah.kode_mk', '=', 'pengampu.kode_mk')
            ->where('pengampu.nip', $request->nip)
            ->where('jadwals.hari', $request->hari)
            ->where('jadwals.semester_tahun', $request->semester_tahun)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jadwals.jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jadwals.jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jadwals.jam_mulai', '<=', $request->jam_mulai)
                            ->where('jadwals.jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($konflikDosen) {
            return back()->withErrors(['error' => 'Dosen sudah memiliki jadwal mengajar pada waktu tersebut!'])->withInput();
        }

        try {
            // Buat jadwal
            $jadwal = Jadwal::create([
                'kode_mk' => $request->kode_mk,
                'id_ruang' => $request->id_ruang,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'semester_tahun' => $request->semester_tahun
            ]);

            // Assign dosen sebagai pengampu mata kuliah
            $matakuliah = Matakuliah::find($request->kode_mk);
            $matakuliah->pengampu()->detach();
            $matakuliah->pengampu()->attach($request->nip);

            // HAPUS BAGIAN AUTO-GENERATE KRS - Mahasiswa akan mengambil jadwal sendiri
            // Krs::create([
            //     'nim' => null,
            //     'kode_mk' => $request->kode_mk,
            //     'semester' => $request->semester_tahun,
            //     'status' => 'tersedia'
            // ]);

            return redirect()->route('admin.jadwal.index')
                            ->with('success', 'Jadwal berhasil ditambahkan dan dosen telah ditetapkan sebagai pengampu.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['matakuliah.pengampu', 'ruang']);
        
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $matakuliah = Matakuliah::orderBy('semester')->orderBy('kode_mk')->get();
        $ruang = Ruang::orderBy('id_ruang')->get();
        $dosen = Dosen::orderBy('nama')->get(); // Tambah data dosen
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $semesters = ['2024/2025 Ganjil', '2024/2025 Genap', '2025/2026 Ganjil', '2025/2026 Genap'];
        
        // Ambil dosen pengampu saat ini
        $currentDosen = $jadwal->matakuliah->pengampu->first();
        
        return view('admin.jadwal.edit', compact('jadwal', 'matakuliah', 'ruang', 'dosen', 'hari', 'semesters', 'currentDosen'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kode_mk' => 'required|exists:matakuliah,kode_mk',
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'nip' => 'required|exists:dosen,nip',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'semester_tahun' => 'required|string|max:20'
        ]);

        // Cek konflik jadwal ruang (kecuali jadwal yang sedang diedit)
        $konflik = Jadwal::where('id_ruang', $request->id_ruang)
            ->where('hari', $request->hari)
            ->where('semester_tahun', $request->semester_tahun)
            ->where('id', '!=', $jadwal->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($konflik) {
            return back()->withErrors(['error' => 'Ruang sudah digunakan pada waktu tersebut!'])->withInput();
        }

        // Cek konflik jadwal dosen (kecuali jadwal yang sedang diedit)
        $konflikDosen = Jadwal::join('matakuliah', 'jadwals.kode_mk', '=', 'matakuliah.kode_mk')
            ->join('pengampu', 'matakuliah.kode_mk', '=', 'pengampu.kode_mk')
            ->where('pengampu.nip', $request->nip)
            ->where('jadwals.hari', $request->hari)
            ->where('jadwals.semester_tahun', $request->semester_tahun)
            ->where('jadwals.id', '!=', $jadwal->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jadwals.jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jadwals.jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jadwals.jam_mulai', '<=', $request->jam_mulai)
                            ->where('jadwals.jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($konflikDosen) {
            return back()->withErrors(['error' => 'Dosen sudah memiliki jadwal mengajar pada waktu tersebut!'])->withInput();
        }

        try {
            // Update jadwal
            $jadwal->update([
                'kode_mk' => $request->kode_mk,
                'id_ruang' => $request->id_ruang,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'semester_tahun' => $request->semester_tahun
            ]);

            // Update pengampu mata kuliah
            $matakuliah = Matakuliah::find($request->kode_mk);
            
            // Hapus pengampu lama
            $matakuliah->pengampu()->detach();
            
            // Tambah pengampu baru
            $matakuliah->pengampu()->attach($request->nip);

            return redirect()->route('admin.jadwal.index')
                            ->with('success', 'Jadwal berhasil diperbarui dan dosen telah ditetapkan sebagai pengampu.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
                        ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function calendar(Request $request)
    {
        $semester_filter = $request->get('semester', '2024/2025 Ganjil');
        $search = $request->get('search', '');
        
        // Get all semesters for filter
        $semesters = Jadwal::select('semester_tahun')
            ->distinct()
            ->orderBy('semester_tahun', 'desc')
            ->pluck('semester_tahun');
        
        // Get jadwals with filters
        $jadwals = Jadwal::with(['matakuliah.pengampu', 'ruang'])
            ->where('semester_tahun', $semester_filter)
            ->when($search, function($query, $search) {
                return $query->whereHas('matakuliah', function($q) use ($search) {
                    $q->where('nama_mk', 'like', "%{$search}%")
                      ->orWhere('kode_mk', 'like', "%{$search}%");
                });
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
        
        // Group jadwals by day
        $jadwalsByDay = $jadwals->groupBy('hari');
        
        // Calculate statistics
        $totalJadwal = $jadwals->count();
        $totalMatakuliah = $jadwals->pluck('matakuliah.kode_mk')->unique()->count();
        $totalRuang = $jadwals->pluck('id_ruang')->unique()->count();
        $totalDosen = $jadwals->pluck('matakuliah.pengampu')->flatten()->pluck('nip')->unique()->count();
        
        return view('admin.jadwal.calendar', compact(
            'jadwalsByDay', 
            'semesters', 
            'semester_filter', 
            'search',
            'totalJadwal',
            'totalMatakuliah', 
            'totalRuang',
            'totalDosen'
        ));
    }
}