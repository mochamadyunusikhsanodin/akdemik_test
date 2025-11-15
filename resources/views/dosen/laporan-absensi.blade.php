@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Dosen</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Laporan Absensi</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Laporan Absensi</h6>
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
                            <h6 class="text-white text-capitalize ps-3">Laporan Absensi Mahasiswa</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <!-- Filter -->
                        <form method="GET" class="px-3 mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline">
                                        <label class="form-label">Mata Kuliah</label>
                                        <select name="kode_mk" class="form-control" onchange="this.form.submit()">
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach($matakuliahs as $mk)
                                                <option value="{{ $mk->kode_mk }}" {{ $selectedMk == $mk->kode_mk ? 'selected' : '' }}>
                                                    {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline">
                                        <label class="form-label">Bulan</label>
                                        <input type="month" name="bulan" class="form-control" value="{{ $bulan }}" onchange="this.form.submit()">
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        @if($selectedMk && $laporan->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mahasiswa</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Pertemuan</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hadir</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tidak Hadir</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Izin</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sakit</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($laporan as $data)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $data['mahasiswa']->nama }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $data['mahasiswa']->nim }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $data['total'] }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-success">{{ $data['hadir'] }}</span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-danger">{{ $data['tidak_hadir'] }}</span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-warning">{{ $data['izin'] }}</span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-info">{{ $data['sakit'] }}</span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="text-secondary text-xs font-weight-bold">{{ $data['persentase_kehadiran'] }}%</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($selectedMk)
                            <div class="text-center py-4">
                                <p class="text-secondary mb-0">Tidak ada data absensi untuk bulan yang dipilih.</p>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-secondary mb-0">Pilih mata kuliah untuk melihat laporan absensi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection