<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\KrsMahasiswa;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Role helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    public function getRoleDisplayName()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'dosen' => 'Dosen',
            'mahasiswa' => 'Mahasiswa',
            default => 'Unknown'
        };
    }

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'nim_nip');
    }

    // Relasi dengan Dosen
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'nip', 'nim_nip');
    }

    // Method untuk mendapatkan URL avatar
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return asset('assets/img/team-2.jpg'); // default avatar
    }
}