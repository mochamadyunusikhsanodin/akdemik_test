<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengampu', function (Blueprint $table) {
            $table->string('kode_mk', 10);
            $table->string('nip', 20);
            $table->timestamps();
            
            $table->primary(['kode_mk', 'nip']);
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengampu');
    }
};