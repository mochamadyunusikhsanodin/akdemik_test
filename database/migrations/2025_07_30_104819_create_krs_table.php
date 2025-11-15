<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20);
            $table->unsignedBigInteger('jadwal_id');
            $table->string('semester_tahun', 20);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
            
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('id')->on('jadwals')->onDelete('cascade');
            $table->unique(['nim', 'jadwal_id']); // Prevent duplicate enrollment
        });
    }

    public function down()
    {
        Schema::dropIfExists('krs');
    }
};