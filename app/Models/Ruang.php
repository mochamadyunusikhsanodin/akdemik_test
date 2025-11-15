<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_ruang',
        'nama_ruang'
    ];

    // Relasi dengan jadwal akademik (gunakan tabel jadwals)
    public function jadwalAkademik()
    {
        return $this->hasMany(Jadwal::class, 'id_ruang', 'id_ruang');
    }

    // Alias untuk jadwals
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'id_ruang', 'id_ruang');
    }
}