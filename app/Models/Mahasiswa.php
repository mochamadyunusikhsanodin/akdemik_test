<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'alamat',
        'no_hp',
        'semester',
        'id_gol'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'nim', 'nim_nip');
    }

    // Relasi dengan KRS
    public function krs()
    {
        return $this->hasMany(Krs::class, 'nim', 'nim');
    }

    // Method untuk mendapatkan jadwal kuliah mahasiswa
    public function getJadwalKuliah($semester = null)
    {
        $query = $this->krs()
            ->with(['jadwal.matakuliah', 'jadwal.ruang'])
            ->where('status', 'approved');
            
        if ($semester) {
            $query->where('semester_tahun', $semester);
        }
        
        return $query->get();
    }

    // Relasi dengan Golongan
    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_gol', 'id_gol');
    }

    // Relasi dengan Presensi
    public function presensiAkademik()
    {
        return $this->hasMany(PresensiAkademik::class, 'nim', 'nim');
    }
}