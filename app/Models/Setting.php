<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description'
    ];

    // Status KRS sederhana
    public static function isKrsOpen()
    {
        $setting = self::where('key', 'krs_status')->first();
        return $setting && $setting->value === 'open';
    }

    public static function openKrs()
    {
        self::updateOrCreate(
            ['key' => 'krs_status'],
            ['value' => 'open', 'description' => 'Status KRS']
        );
    }

    public static function closeKrs()
    {
        self::updateOrCreate(
            ['key' => 'krs_status'],
            ['value' => 'closed', 'description' => 'Status KRS']
        );
    }

    public static function getKrsStatus()
    {
        $setting = self::where('key', 'krs_status')->first();
        return $setting ? $setting->value : 'closed';
    }

    public static function getKrsStatusText()
    {
        $status = self::getKrsStatus();
        return $status === 'open' ? 'Dibuka' : 'Ditutup';
    }

    public static function set($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description
            ]
        );
    }

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Method untuk limit KRS
    public static function getMaxSks($default = 24)
    {
        return (int) self::get('max_sks_per_semester', $default);
    }

    public static function getMaxMatakuliah($default = 8)
    {
        return (int) self::get('max_matakuliah_per_semester', $default);
    }

    public static function setMaxSks($value)
    {
        return self::set('max_sks_per_semester', $value, 'Maksimal SKS per semester');
    }

    public static function setMaxMatakuliah($value)
    {
        return self::set('max_matakuliah_per_semester', $value, 'Maksimal mata kuliah per semester');
    }

    // Method untuk cek limit KRS mahasiswa
    public static function checkKrsLimit($nim, $semesterTahun, $newSks = 0)
    {
        $maxSks = self::getMaxSks();
        $maxMatakuliah = self::getMaxMatakuliah();
        
        // Hitung total SKS dan mata kuliah yang sudah diambil
        $currentKrs = \App\Models\Krs::where('nim', $nim)
            ->where('semester_tahun', $semesterTahun)
            ->with('jadwal.matakuliah')
            ->get();
            
        $totalSks = $currentKrs->sum(function($krs) {
            return $krs->jadwal->matakuliah->sks ?? 0;
        });
        
        $totalMatakuliah = $currentKrs->count();
        
        return [
            'can_add' => ($totalSks + $newSks <= $maxSks) && ($totalMatakuliah < $maxMatakuliah),
            'current_sks' => $totalSks,
            'current_matakuliah' => $totalMatakuliah,
            'max_sks' => $maxSks,
            'max_matakuliah' => $maxMatakuliah,
            'new_total_sks' => $totalSks + $newSks
        ];
    }
}
