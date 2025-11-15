<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum untuk menambah status 'terlambat'
        DB::statement("ALTER TABLE presensi_akademik MODIFY COLUMN status_kehadiran ENUM('hadir', 'tidak_hadir', 'izin', 'sakit', 'terlambat') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE presensi_akademik MODIFY COLUMN status_kehadiran ENUM('hadir', 'tidak_hadir', 'izin', 'sakit') NOT NULL");
    }
};