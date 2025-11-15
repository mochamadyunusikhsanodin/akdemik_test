@extends('layouts.app')

@section('page-title', 'Dashboard Mahasiswa')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Mahasiswa</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Dashboard Mahasiswa</h6>
            </nav>
        </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">school</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">KRS Diambil</p>
                            <h4 class="mb-0">{{ $stats['krs_diambil'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">schedule</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Jadwal Hari Ini</p>
                            <h4 class="mb-0">{{ $stats['jadwal_hari_ini'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">credit_score</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total SKS</p>
                            <h4 class="mb-0">{{ $stats['total_sks_diambil'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">how_to_reg</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Kehadiran Bulan Ini</p>
                            <h4 class="mb-0">{{ $stats['presensi_bulan_ini'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Jadwal Hari Ini -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Jadwal Kuliah Hari Ini</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($jadwalHariIni->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mata Kuliah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ruang</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jadwalHariIni as $jadwal)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $jadwal->matakuliah->nama_mk }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $jadwal->matakuliah->kode_mk }} ({{ $jadwal->matakuliah->sks }} SKS)</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $jadwal->ruang->nama_ruang }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $jadwal->ruang->gedung }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-secondary mb-0">Tidak ada jadwal kuliah hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Menu Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('mahasiswa.jadwal') }}" class="btn bg-gradient-success w-100">
                                    <i class="fas fa-calendar me-2"></i>
                                    Lihat Jadwal
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('mahasiswa.jadwal.tersedia') }}" class="btn bg-gradient-primary w-100">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Jadwal Tersedia
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('mahasiswa.absensi') }}" class="btn bg-gradient-info w-100">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Riwayat Absensi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection