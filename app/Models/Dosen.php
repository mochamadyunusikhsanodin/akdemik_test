<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'alamat',
        'no_hp'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nim_nip');
    }
    
    // Relasi dengan Mata Kuliah yang diampu
    public function matakuliahs()
    {
        return $this->belongsToMany(Matakuliah::class, 'pengampu', 'nip', 'kode_mk');
    }
}