<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 15)->primary();
            $table->string('nama', 100);
            $table->string('alamat', 200)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->integer('semester');
            $table->string('id_gol', 10);
            $table->timestamps();
            
            $table->foreign('id_gol')->references('id_gol')->on('golongan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};