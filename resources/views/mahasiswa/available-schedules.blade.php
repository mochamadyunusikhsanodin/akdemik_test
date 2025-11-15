@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Sistem Akademik</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Mata Kuliah</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Data Mata Kuliah</h6>
            </nav>
        </div>
    </nav>
    <!-- End Navbar -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Jadwal Mata Kuliah Tersedia</h6>
                        <p class="text-white text-sm ps-3 mb-0">Pilih mata kuliah yang ingin Anda ambil</p>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mx-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($availableSchedules->count() > 0)
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            @if($availableSchedules->has($hari))
                                <div class="mx-3 mb-4">
                                    <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">{{ $hari }}</h6>
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mata Kuliah</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dosen</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ruang</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKS</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($availableSchedules[$hari] as $jadwal)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $jadwal->matakuliah->nama_mk }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $jadwal->matakuliah->kode_mk }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($jadwal->matakuliah->pengampu->isNotEmpty())
                                                            @foreach($jadwal->matakuliah->pengampu as $pengampu)
                                                                <p class="text-xs font-weight-bold mb-0">{{ $pengampu->nama }}</p>
                                                            @endforeach
                                                        @else
                                                            <p class="text-xs text-secondary mb-0">Belum ada dosen</p>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $jadwal->ruang->nama_ruang }}</p>
                                                        <p class="text-xs text-secondary mb-0">{{ $jadwal->ruang->gedung }}</p>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="badge badge-sm bg-gradient-success">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-xs font-weight-bold">{{ $jadwal->matakuliah->sks }} SKS</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <form action="{{ route('mahasiswa.ambil-jadwal', $jadwal->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm bg-gradient-primary mb-0" 
                                                                    onclick="return confirm('Apakah Anda yakin ingin mengambil mata kuliah ini?')">
                                                                <i class="fas fa-plus"></i> Ambil
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="alert alert-info mx-3">
                            <i class="material-icons">info</i> Tidak ada jadwal mata kuliah yang tersedia untuk semester ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Filter Semester</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('mahasiswa.jadwal-tersedia') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Semester</label>
                                    <select class="form-control" name="semester">
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester }}" {{ $semester_filter === $semester ? 'selected' : '' }}>
                                                {{ $semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn bg-gradient-primary">Filter</button>
                                <a href="{{ route('mahasiswa.jadwal-tersedia') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection