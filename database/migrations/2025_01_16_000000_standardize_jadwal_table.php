<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Backup data dari jadwal_akademik jika ada
        $jadwalAkademikData = DB::table('jadwal_akademik')->get();
        
        // Migrate data dari jadwal_akademik ke jadwals jika belum ada
        foreach ($jadwalAkademikData as $jadwal) {
            DB::table('jadwals')->insertOrIgnore([
                'kode_mk' => $jadwal->kode_mk,
                'id_ruang' => $jadwal->id_ruang,
                'hari' => $jadwal->hari,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
                'semester_tahun' => '2024/2025 Ganjil', // default semester
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Drop tabel jadwal_akademik yang tidak digunakan
        Schema::dropIfExists('jadwal_akademik');
    }

    public function down()
    {
        // Recreate jadwal_akademik table if needed
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
};