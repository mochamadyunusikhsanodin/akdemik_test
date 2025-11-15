<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_akademik', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('status_kehadiran', ['hadir', 'tidak_hadir', 'izin', 'sakit']);
            $table->string('nim', 15);
            $table->string('kode_mk', 10);
            $table->timestamps();
            
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_akademik');
    }
};