@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Admin</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">KRS</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Data KRS</h6>
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
                        <h6 class="text-white text-capitalize ps-3">Jadwal Mengajar</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <!-- Filter -->
                    <div class="row mb-3 px-3">
                        <div class="col-md-4">
                            <form method="GET">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Semester</label>
                                    <select name="semester" class="form-control" onchange="this.form.submit()">
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester }}" {{ $semester_filter == $semester ? 'selected' : '' }}>
                                                {{ $semester }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Jadwal per Hari -->
                    @if($jadwals->count() > 0)
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            @if(isset($jadwals[$hari]))
                                <div class="px-3 mb-4">
                                    <h6 class="mb-3">{{ $hari }}</h6>
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mata Kuliah</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ruang</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($jadwals[$hari] as $jadwal)
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
                                                        <p class="text-xs font-weight-bold mb-0">{{ $jadwal->ruang->nama_ruang }}</p>
                                                        <p class="text-xs text-secondary mb-0">{{ $jadwal->ruang->id_ruang }}</p>
                                                    </td>
                                                    <td class="align-middle text-center text-sm">
                                                        <span class="badge badge-sm bg-gradient-success">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-xs font-weight-bold">{{ $jadwal->matakuliah->sks }} SKS</span>
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
                        <div class="px-3">
                            <div class="alert alert-info">
                                <i class="material-icons">info</i> Tidak ada jadwal mengajar untuk semester ini.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection