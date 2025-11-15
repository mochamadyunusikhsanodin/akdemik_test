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
                        <h6 class="text-white text-capitalize ps-3">Absensi Mahasiswa</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-3">
                            <i class="material-icons">check</i> {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger mx-3">
                            <i class="material-icons">error</i> {{ $errors->first() }}
                        </div>
                    @endif
                    
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
                            <div class="col-md-3">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ $selectedDate }}" onchange="this.form.submit()">
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    @if($selectedMk && $matakuliah)
                        <!-- Kontrol Aktivasi Absensi -->
                        <div class="px-3 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Pengaturan Absensi - {{ $matakuliah->nama_mk }}</h6>
                                </div>
                                <div class="card-body">
                                    
                                    // To:
                                    <form method="POST" action="{{ route('dosen.toggle-absensi') }}">
                                        @csrf
                                        <input type="hidden" name="kode_mk" value="{{ $selectedMk }}">
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="absensi_aktif" value="1" 
                                                           {{ $matakuliah->absensi_aktif ? 'checked' : '' }} id="absensiSwitch">
                                                    <label class="form-check-label" for="absensiSwitch">
                                                        <strong>{{ $matakuliah->absensi_aktif ? 'Absensi Aktif' : 'Absensi Nonaktif' }}</strong>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-outline">
                                                    <label class="form-label">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai_absensi" class="form-control" 
                                                           value="{{ $matakuliah->tanggal_mulai_absensi ? $matakuliah->tanggal_mulai_absensi->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group input-group-outline">
                                                    <label class="form-label">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai_absensi" class="form-control" 
                                                           value="{{ $matakuliah->tanggal_selesai_absensi ? $matakuliah->tanggal_selesai_absensi->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="material-icons">save</i> Simpan Pengaturan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$absensiAktif)
                            <div class="px-3">
                                <div class="alert alert-warning">
                                    <i class="material-icons">warning</i> 
                                    Absensi untuk mata kuliah ini sedang nonaktif. Silakan aktifkan terlebih dahulu untuk melakukan absensi.
                                </div>
                            </div>
                        @elseif($mahasiswas->count() > 0)
                            <!-- Form Absensi -->
                            <form method="POST" action="{{ route('dosen.absensi.store') }}" class="px-3">
                                @csrf
                                <input type="hidden" name="kode_mk" value="{{ $selectedMk }}">
                                <input type="hidden" name="tanggal" value="{{ $selectedDate }}">
                                
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mahasiswa</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mahasiswas as $index => $mahasiswa)
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $mahasiswa->nama }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $mahasiswa->nim }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        @php
                                                            $currentStatus = $presensiData->get($mahasiswa->nim)?->status_kehadiran ?? 'hadir';
                                                        @endphp
                                                        
                                                        <div class="form-check form-check-radio">
                                                            <input class="form-check-input" type="radio" name="presensi[{{ $mahasiswa->nim }}]" value="hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-success">Hadir</label>
                                                        </div>
                                                        
                                                        <div class="form-check form-check-radio">
                                                            <input class="form-check-input" type="radio" name="presensi[{{ $mahasiswa->nim }}]" value="tidak_hadir" {{ $currentStatus == 'tidak_hadir' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-danger">Tidak Hadir</label>
                                                        </div>
                                                        
                                                        <div class="form-check form-check-radio">
                                                            <input class="form-check-input" type="radio" name="presensi[{{ $mahasiswa->nim }}]" value="izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-warning">Izin</label>
                                                        </div>
                                                        
                                                        <div class="form-check form-check-radio">
                                                            <input class="form-check-input" type="radio" name="presensi[{{ $mahasiswa->nim }}]" value="sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}>
                                                            <label class="form-check-label text-info">Sakit</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">save</i> Simpan Presensi
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="px-3">
                                <div class="alert alert-info">
                                    <i class="material-icons">info</i> Tidak ada mahasiswa yang mengambil mata kuliah ini.
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="px-3">
                            <div class="alert alert-warning">
                                <i class="material-icons">warning</i> Silakan pilih mata kuliah terlebih dahulu.
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