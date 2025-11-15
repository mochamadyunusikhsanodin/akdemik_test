<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiAkademik extends Model
{
    use HasFactory;

    protected $table = 'presensi_akademik';
    
    protected $fillable = [
        'tanggal',
        'status_kehadiran',
        'nim',
        'kode_mk'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    // Relasi dengan Matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_mk', 'kode_mk');
    }

    // Scope untuk filter berdasarkan mata kuliah
    public function scopeByMatakuliah($query, $kodeMk)
    {
        return $query->where('kode_mk', $kodeMk);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_kehadiran', $status);
    }
}