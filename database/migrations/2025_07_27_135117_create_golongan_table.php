<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('golongan', function (Blueprint $table) {
            $table->string('id_gol', 10)->primary();
            $table->string('nama_gol', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golongan');
    }
};