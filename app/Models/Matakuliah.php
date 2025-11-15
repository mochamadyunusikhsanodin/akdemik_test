<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Krs;
use App\Models\KrsMahasiswa;
use App\Models\JadwalAkademik;
use App\Models\Dosen;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'absensi_aktif',
        'tanggal_mulai_absensi',
        'tanggal_selesai_absensi',
        'jam_buka_absensi',
        'jam_tutup_absensi',
        'toleransi_keterlambatan'
    ];

    protected $casts = [
        'tanggal_mulai_absensi' => 'date',
        'tanggal_selesai_absensi' => 'date',
        'jam_buka_absensi' => 'datetime:H:i',
        'jam_tutup_absensi' => 'datetime:H:i',
        'absensi_aktif' => 'boolean'
    ];

    // Method untuk cek apakah absensi sedang aktif
    public function isAbsensiAktif()
    {
        if (!$this->absensi_aktif) {
            return false;
        }
        
        $today = now()->toDateString();
        
        if ($this->tanggal_mulai_absensi && $today < $this->tanggal_mulai_absensi) {
            return false;
        }
        
        if ($this->tanggal_selesai_absensi && $today > $this->tanggal_selesai_absensi) {
            return false;
        }
        
        return true;
    }

    // Method untuk cek apakah waktu absensi sedang buka
    public function isWaktuAbsensiTerbuka()
    {
        if (!$this->isAbsensiAktif()) {
            return false;
        }
        
        $currentTime = now()->format('H:i');
        
        if ($this->jam_buka_absensi && $currentTime < $this->jam_buka_absensi) {
            return false;
        }
        
        if ($this->jam_tutup_absensi && $currentTime > $this->jam_tutup_absensi) {
            return false;
        }
        
        return true;
    }

    // Relasi dengan KRS


    // Relasi dengan Jadwal Akademik
    public function jadwalAkademik()
    {
        return $this->hasMany(JadwalAkademik::class, 'kode_mk', 'kode_mk');
    }

    // Relasi dengan Jadwal (alias untuk jadwalAkademik)
    // In Matakuliah model
public function jadwals()
{
    return $this->hasMany(Jadwal::class, 'kode_mk', 'kode_mk');
}

    // Relasi dengan Pengampu (Dosen)
    public function pengampu()
    {
        return $this->belongsToMany(Dosen::class, 'pengampu', 'kode_mk', 'nip');
    }

    // Add this new relationship method
    public function jadwal()
    {
        return $this->hasOne(Jadwal::class, 'kode_mk', 'kode_mk');
    }

    // Relasi dengan KRS
    // Relasi dengan KRS
    public function krs()
    {
        return $this->hasManyThrough(
            Krs::class,        // Model target
            Jadwal::class,     // Model perantara
            'kode_mk',         // Foreign key di jadwals table
            'jadwal_id',       // Foreign key di krs table
            'kode_mk',         // Local key di matakuliah table
            'id'               // Local key di jadwals table
        );
    }

    // Relasi dengan Presensi
    public function presensiAkademik()
    {
        return $this->hasMany(PresensiAkademik::class, 'kode_mk', 'kode_mk');
    }
}