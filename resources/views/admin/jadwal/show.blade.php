@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Detail</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Detail Jadwal</h6>
            </nav>
        </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header Card -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Detail Jadwal Kuliah</h6>
                                <p class="text-sm mb-0">Informasi lengkap jadwal mata kuliah</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.jadwal.edit', $jadwal) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="material-icons text-sm">edit</i> Edit
                                </a>
                                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="material-icons text-sm">arrow_back</i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <!-- Informasi Mata Kuliah -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center border-radius-md me-2">
                                        <i class="material-icons opacity-10">school</i>
                                    </div>
                                    <h6 class="mb-0">Informasi Mata Kuliah</h6>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-item mb-3">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Kode Mata Kuliah</label>
                                            <p class="text-sm font-weight-bold mb-0">{{ $jadwal->matakuliah->kode_mk }}</p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Nama Mata Kuliah</label>
                                            <p class="text-sm font-weight-bold mb-0">{{ $jadwal->matakuliah->nama_mk }}</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="info-item mb-3">
                                                    <label class="text-xs font-weight-bold text-uppercase opacity-7">SKS</label>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        <span class="badge bg-gradient-info">{{ $jadwal->matakuliah->sks }} SKS</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-item mb-3">
                                                    <label class="text-xs font-weight-bold text-uppercase opacity-7">Semester</label>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        <span class="badge bg-gradient-secondary">Semester {{ $jadwal->matakuliah->semester }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Dosen Pengampu</label>
                                            <p class="text-sm font-weight-bold mb-0">
                                                @if($jadwal->matakuliah->pengampu->count() > 0)
                                                    <i class="material-icons text-sm me-1">person</i>
                                                    {{ $jadwal->matakuliah->pengampu->first()->nama }}
                                                @else
                                                    <span class="text-muted"><i class="material-icons text-sm me-1">person_off</i>Belum ada dosen pengampu</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Jadwal -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm bg-gradient-success shadow text-center border-radius-md me-2">
                                        <i class="material-icons opacity-10">schedule</i>
                                    </div>
                                    <h6 class="mb-0">Informasi Jadwal</h6>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-item mb-3">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Ruang Kuliah</label>
                                            <p class="text-sm font-weight-bold mb-0">
                                                <i class="material-icons text-sm me-1">room</i>
                                                {{ $jadwal->ruang->nama_ruang }} 
                                                <span class="text-muted">({{ $jadwal->ruang->id_ruang }})</span>
                                            </p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Hari</label>
                                            <p class="text-sm font-weight-bold mb-0">
                                                <span class="badge bg-gradient-primary">{{ $jadwal->hari }}</span>
                                            </p>
                                        </div>
                                        <div class="info-item mb-3">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Waktu</label>
                                            <p class="text-sm font-weight-bold mb-0">
                                                <i class="material-icons text-sm me-1">access_time</i>
                                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                            </p>
                                        </div>
                                        <div class="info-item">
                                            <label class="text-xs font-weight-bold text-uppercase opacity-7">Semester/Tahun Akademik</label>
                                            <p class="text-sm font-weight-bold mb-0">
                                                <span class="badge bg-gradient-warning">{{ $jadwal->semester_tahun }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center border-radius-md me-2">
                                        <i class="material-icons opacity-10">info</i>
                                    </div>
                                    <h6 class="mb-0">Informasi Tambahan</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="icon icon-shape icon-lg bg-gradient-primary shadow mx-auto">
                                                <i class="material-icons opacity-10">groups</i>
                                            </div>
                                            <h6 class="mt-3 mb-1">Mahasiswa Terdaftar</h6>
                                            <p class="text-sm mb-0">{{ $jadwal->krs->count() ?? 0 }} Mahasiswa</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="icon icon-shape icon-lg bg-gradient-success shadow mx-auto">
                                                <i class="material-icons opacity-10">event_available</i>
                                            </div>
                                            <h6 class="mt-3 mb-1">Status</h6>
                                            <p class="text-sm mb-0">
                                                <span class="badge bg-gradient-success">Aktif</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="icon icon-shape icon-lg bg-gradient-warning shadow mx-auto">
                                                <i class="material-icons opacity-10">update</i>
                                            </div>
                                            <h6 class="mt-3 mb-1">Terakhir Diupdate</h6>
                                            <p class="text-sm mb-0">{{ $jadwal->updated_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection