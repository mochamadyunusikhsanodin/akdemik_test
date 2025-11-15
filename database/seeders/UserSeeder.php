<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@akademik.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'location' => 'Jakarta',
            'about' => 'Administrator Sistem Informasi Akademik'
        ]);

        // Dosen
        User::create([
            'name' => 'Dr. Ahmad Wijaya, M.Kom',
            'email' => 'ahmad.wijaya@akademik.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nim_nip' => '198501012010011001',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Teknik Informatika',
            'phone' => '081234567891',
            'location' => 'Bandung',
            'about' => 'Dosen Teknik Informatika'
        ]);

        User::create([
            'name' => 'Prof. Siti Nurhaliza, Ph.D',
            'email' => 'siti.nurhaliza@akademik.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nim_nip' => '197803152005012002',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Sistem Informasi',
            'phone' => '081234567892',
            'location' => 'Jakarta',
            'about' => 'Dosen Sistem Informasi'
        ]);

        // Mahasiswa
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@student.akademik.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '2021110001',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Teknik Informatika',
            'semester' => '6',
            'phone' => '081234567893',
            'location' => 'Surabaya',
            'about' => 'Mahasiswa Teknik Informatika'
        ]);

        User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari.dewi@student.akademik.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '2021120002',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Sistem Informasi',
            'semester' => '4',
            'phone' => '081234567894',
            'location' => 'Yogyakarta',
            'about' => 'Mahasiswa Sistem Informasi'
        ]);
    }
}