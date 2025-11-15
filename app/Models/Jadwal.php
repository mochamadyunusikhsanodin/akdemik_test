<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mk',
        'id_ruang',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'semester_tahun'
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_mk', 'kode_mk');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }

    // Add this missing relationship
    public function krs()
    {
        return $this->hasMany(Krs::class, 'jadwal_id', 'id');
    }

    // Scope untuk filter berdasarkan semester
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester_tahun', $semester);
    }

    // Accessor untuk format waktu
    public function getJamAttribute()
    {
        return $this->jam_mulai . ' - ' . $this->jam_selesai;
    }
}
