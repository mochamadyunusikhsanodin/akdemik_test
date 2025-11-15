<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 10);
            $table->string('id_ruang', 10);
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('semester_tahun', 20); // contoh: 2024/2025 Ganjil
            $table->timestamps();
            
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah')->onDelete('cascade');
            $table->foreign('id_ruang')->references('id_ruang')->on('ruang')->onDelete('cascade');
            
            // Unique constraint untuk mencegah bentrok jadwal
            $table->unique(['id_ruang', 'hari', 'jam_mulai', 'semester_tahun'], 'unique_jadwal_ruang');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwals');
    }
};