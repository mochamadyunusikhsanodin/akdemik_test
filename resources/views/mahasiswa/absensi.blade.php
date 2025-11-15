@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Mahasiswa</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Absensi</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Absensi Kuliah</h6>
            </nav>
        </div>
    </nav>
    <!-- End Navbar -->
    
    <div class="container-fluid py-4">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Filter Absensi</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('mahasiswa.absensi') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3">
                                        <select name="kode_mk" class="form-control">
                                            <option value="">Semua Mata Kuliah</option>
                                            @foreach($matakuliahs as $mk)
                                                <option value="{{ $mk->kode_mk }}" {{ $kode_mk == $mk->kode_mk ? 'selected' : '' }}>
                                                    {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3">
                                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn bg-gradient-primary">Filter</button>
                                    <a href="{{ route('mahasiswa.absensi') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Absensi Hari Ini -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Absensi Hari Ini</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($matakuliahs as $mk)
                                @php
                                    $jadwalHariIni = $mk->jadwals->where('hari', now()->locale('id')->dayName)->first();
                                    $sudahAbsen = isset($sudahAbsenHariIni[$mk->kode_mk]) ? $sudahAbsenHariIni[$mk->kode_mk] : false;
                                @endphp
                                
                                @if($jadwalHariIni)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $mk->nama_mk }}</h6>
                                                <p class="card-text text-sm">
                                                    <strong>Kode:</strong> {{ $mk->kode_mk }}<br>
                                                    <strong>Waktu:</strong> {{ $jadwalHariIni->jam_mulai }} - {{ $jadwalHariIni->jam_selesai }}<br>
                                                    <strong>Ruang:</strong> {{ $jadwalHariIni->ruang->nama_ruang ?? 'TBA' }}
                                                </p>
                                                
                                                @if($sudahAbsen)
                                                    <span class="badge bg-gradient-success">Sudah Absen</span>
                                                @elseif($mk->isWaktuAbsensiTerbuka())
                                                    <button class="btn btn-sm bg-gradient-primary" onclick="doAbsensi('{{ $mk->kode_mk }}')">
                                                        <i class="fas fa-check"></i> Absen Sekarang
                                                    </button>
                                                @else
                                                    <span class="badge bg-gradient-secondary">Absensi Ditutup</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Absensi -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Riwayat Absensi</h6>
                    </div>
                    <div class="card-body">
                        @if($absensi->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mata Kuliah</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensi as $index => $item)
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $index + 1 }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $item->tanggal->format('d/m/Y') }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $item->matakuliah->nama_mk }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $item->matakuliah->kode_mk }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($item->status_kehadiran == 'hadir')
                                                    <span class="badge badge-sm bg-gradient-success">Hadir</span>
                                                @elseif($item->status_kehadiran == 'terlambat')
                                                    <span class="badge badge-sm bg-gradient-warning">Terlambat</span>
                                                @elseif($item->status_kehadiran == 'tidak_hadir')
                                                    <span class="badge badge-sm bg-gradient-danger">Tidak Hadir</span>
                                                @elseif($item->status_kehadiran == 'izin')
                                                    <span class="badge badge-sm bg-gradient-info">Izin</span>
                                                @elseif($item->status_kehadiran == 'sakit')
                                                    <span class="badge badge-sm bg-gradient-secondary">Sakit</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Belum ada data absensi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Loading -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Sedang memproses absensi...</p>
            </div>
        </div>
    </div>
</div>

<script>
function doAbsensi(kodeMk) {
    if (!navigator.geolocation) {
        alert('Geolocation tidak didukung oleh browser ini.');
        return;
    }
    
    $('#loadingModal').modal('show');
    
    navigator.geolocation.getCurrentPosition(function(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        
        fetch('{{ route("mahasiswa.absensi.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                kode_mk: kodeMk,
                latitude: latitude,
                longitude: longitude
            })
        })
        .then(response => response.json())
        .then(data => {
            $('#loadingModal').modal('hide');
            
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            $('#loadingModal').modal('hide');
            alert('Terjadi kesalahan saat memproses absensi.');
        });
    }, function(error) {
        $('#loadingModal').modal('hide');
        alert('Tidak dapat mengakses lokasi. Pastikan GPS aktif dan izinkan akses lokasi.');
    });
}
</script>
@endsection