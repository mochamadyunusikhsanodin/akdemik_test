<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Golongan;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $golongans = [
            [
                'id_gol' => 'GOL001',
                'nama_gol' => 'Golongan A',
            ],
            [
                'id_gol' => 'GOL002',
                'nama_gol' => 'Golongan B',
            ],
            [
                'id_gol' => 'GOL003',
                'nama_gol' => 'Golongan C',
            ],
        ];

        foreach ($golongans as $golongan) {
            Golongan::updateOrCreate(
                ['id_gol' => $golongan['id_gol']],
                $golongan
            );
        }
    }
}