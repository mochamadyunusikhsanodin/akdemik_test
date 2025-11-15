<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('hari', 10);
            $table->string('kode_mk', 10);
            $table->string('id_ruang', 10);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
            
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah');
            $table->foreign('id_ruang')->references('id_ruang')->on('ruang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_akademik');
    }
};