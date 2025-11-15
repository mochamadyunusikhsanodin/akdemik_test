<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->string('kode_mk', 10)->primary();
            $table->string('nama_mk', 100);
            $table->integer('sks');
            $table->integer('semester');
            $table->boolean('absensi_aktif')->default(false); // Tambahkan ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
};