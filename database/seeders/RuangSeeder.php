<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruang;

class RuangSeeder extends Seeder
{
    public function run(): void
    {
        $ruangs = [
            ['id_ruang' => 'R001', 'nama_ruang' => 'Ruang Kuliah A'],
            ['id_ruang' => 'R002', 'nama_ruang' => 'Ruang Kuliah B'],
            ['id_ruang' => 'R003', 'nama_ruang' => 'Ruang Kuliah C'],
            ['id_ruang' => 'LAB001', 'nama_ruang' => 'Laboratorium Komputer 1'],
            ['id_ruang' => 'LAB002', 'nama_ruang' => 'Laboratorium Komputer 2'],
        ];

        foreach ($ruangs as $ruang) {
            Ruang::updateOrCreate(
                ['id_ruang' => $ruang['id_ruang']],
                $ruang
            );
        }
    }
}