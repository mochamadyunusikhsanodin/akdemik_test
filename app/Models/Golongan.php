<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongan';
    protected $primaryKey = 'id_gol';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_gol',
        'nama_gol'
    ];

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_gol', 'id_gol');
    }
}