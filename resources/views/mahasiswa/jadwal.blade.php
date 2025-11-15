@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Admin</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Jadwal</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Data Jadwal</h6>
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
                        <h6 class="text-white text-capitalize ps-3">Jadwal Kuliah Saya</h6>
                        <p class="text-white text-sm ps-3 mb-0">Jadwal berdasarkan KRS yang telah disetujui</p>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if(empty($jadwals) || $jadwals->isEmpty())
                        <div class="alert alert-info mx-3">
                            <h5>Belum Ada Jadwal</h5>
                            <p>Anda belum mengambil mata kuliah apapun. Silakan ambil jadwal di menu <a href="{{ route('mahasiswa.available-schedules') }}">Jadwal Tersedia</a>.</p>
                        </div>
                    @else
                        @foreach($jadwals as $hari => $jadwalHari)
                            <div class="mx-3 mb-4">
                                <h6 class="text-primary font-weight-bold mb-3">{{ $hari }}</h6>
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mata Kuliah</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ruang</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dosen</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jadwalHari as $krs)
                                                @if($krs->jadwal && $krs->jadwal->matakuliah && $krs->jadwal->ruang)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm">{{ $krs->jadwal->matakuliah->nama_mk }}</h6>
                                                                    <p class="text-xs text-secondary mb-0">{{ $krs->jadwal->matakuliah->kode_mk }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-sm bg-gradient-success">{{ $krs->jadwal->jam_mulai }} - {{ $krs->jadwal->jam_selesai }}</span>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <span class="text-secondary text-xs font-weight-bold">{{ $krs->jadwal->ruang->nama_ruang ?? 'Ruang tidak tersedia' }}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-xs font-weight-bold">
                                                                @if($krs->jadwal->matakuliah->pengampu && $krs->jadwal->matakuliah->pengampu->isNotEmpty())
                                                                    {{ $krs->jadwal->matakuliah->pengampu->first()->nama }}
                                                                @else
                                                                    Belum ditentukan
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="text-secondary text-xs font-weight-bold">{{ $krs->jadwal->matakuliah->sks }} SKS</span>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
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
                    <form method="GET" action="{{ route('mahasiswa.jadwal') }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <select name="semester" class="form-control">
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester }}" {{ $semester_filter == $semester ? 'selected' : '' }}>
                                                {{ $semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn bg-gradient-primary">Filter</button>
                                <a href="{{ route('mahasiswa.jadwal') }}" class="btn btn-outline-secondary">Reset</a>
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