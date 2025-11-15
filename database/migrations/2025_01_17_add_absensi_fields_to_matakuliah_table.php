<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            // Only add columns that don't already exist
            // absensi_aktif already exists in the original table
            $table->date('tanggal_mulai_absensi')->nullable();
            $table->date('tanggal_selesai_absensi')->nullable();
            $table->time('jam_buka_absensi')->nullable();
            $table->time('jam_tutup_absensi')->nullable();
            $table->integer('toleransi_keterlambatan')->default(15); // dalam menit
        });
    }

    public function down(): void
    {
        Schema::table('matakuliah', function (Blueprint $table) {
            $table->dropColumn([
                // Don't drop absensi_aktif as it's part of the original table
                'tanggal_mulai_absensi', 
                'tanggal_selesai_absensi',
                'jam_buka_absensi',
                'jam_tutup_absensi',
                'toleransi_keterlambatan'
            ]);
        });
    }
};