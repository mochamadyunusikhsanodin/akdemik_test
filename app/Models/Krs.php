<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';
    
    protected $fillable = [
        'nim',
        'jadwal_id', 
        'semester_tahun',
        'status'
    ];

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Relasi dengan Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'id');
    }
    
    // Relasi dengan Matakuliah melalui Jadwal
    public function matakuliah()
    {
        return $this->hasOneThrough(Matakuliah::class, Jadwal::class, 'id', 'kode_mk', 'jadwal_id', 'kode_mk');
    }
}
