@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Admin</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Jadwal Kalender</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Kalender Jadwal</h6>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3 mb-0">Kalender Jadwal Akademik</h6>
                                <div class="me-3">
                                    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-light mb-0">
                                        <i class="material-icons text-sm">Tambah Jadwal</i>&nbsp;&nbsp;
                                    </a>
                                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-info mb-0">
                                        <i class="material-icons text-sm">Tabel</i>&nbsp;&nbsp;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <!-- Filter Semester -->
                        <div class="row px-3 mb-4">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.jadwal.calendar') }}">
                                    <div class="input-group input-group-outline">
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
                            <div class="col-md-6 text-end">
                                <div class="d-flex justify-content-end align-items-center">
                                    <span class="badge bg-gradient-info me-2">Senin</span>
                                    <span class="badge bg-gradient-success me-2">Selasa</span>
                                    <span class="badge bg-gradient-warning me-2">Rabu</span>
                                    <span class="badge bg-gradient-danger me-2">Kamis</span>
                                    <span class="badge bg-gradient-primary me-2">Jumat</span>
                                    <span class="badge bg-gradient-secondary">Sabtu</span>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success mx-3">{{ session('success') }}</div>
                        @endif

                        <!-- Calendar Grid -->
                        <div class="px-3">
                            <div class="row">
                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    $dayColors = [
                                        'Senin' => 'info',
                                        'Selasa' => 'success', 
                                        'Rabu' => 'warning',
                                        'Kamis' => 'danger',
                                        'Jumat' => 'primary',
                                        'Sabtu' => 'secondary'
                                    ];
                                @endphp
                                
                                @foreach($days as $day)
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header bg-gradient-{{ $dayColors[$day] }} text-white text-center py-2">
                                            <h6 class="mb-0 font-weight-bold">{{ $day }}</h6>
                                        </div>
                                        <div class="card-body p-2" style="min-height: 400px; max-height: 400px; overflow-y: auto;">
                                            @if(isset($jadwalsByDay[$day]) && count($jadwalsByDay[$day]) > 0)
                                                @foreach($jadwalsByDay[$day] as $jadwal)
                                                <div class="schedule-item mb-2 p-2 border-radius-md" style="background: linear-gradient(135deg, rgba({{ $dayColors[$day] == 'info' ? '23, 162, 184' : ($dayColors[$day] == 'success' ? '40, 167, 69' : ($dayColors[$day] == 'warning' ? '255, 193, 7' : ($dayColors[$day] == 'danger' ? '220, 53, 69' : ($dayColors[$day] == 'primary' ? '0, 123, 255' : '108, 117, 125')))) }}, 0.1), rgba(255, 255, 255, 0.9)); border-left: 4px solid {{ $dayColors[$day] == 'info' ? '#17a2b8' : ($dayColors[$day] == 'success' ? '#28a745' : ($dayColors[$day] == 'warning' ? '#ffc107' : ($dayColors[$day] == 'danger' ? '#dc3545' : ($dayColors[$day] == 'primary' ? '#007bff' : '#6c757d')))) }};">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <h6 class="text-sm font-weight-bold mb-0 text-truncate" title="{{ $jadwal->matakuliah->nama_mk }}">
                                                            {{ Str::limit($jadwal->matakuliah->nama_mk, 20) }}
                                                        </h6>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle p-1" type="button" data-bs-toggle="dropdown">
                                                                <i class="material-icons text-xs">aksi</i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="{{ route('admin.jadwal.show', $jadwal) }}"><i class="material-icons text-sm me-1"></i>Lihat</a></li>
                                                                <li><a class="dropdown-item" href="{{ route('admin.jadwal.edit', $jadwal) }}"><i class="material-icons text-sm me-1"></i>Edit</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger"><i class="material-icons text-sm me-1"></i>Hapus</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <p class="text-xs text-secondary mb-1">{{ $jadwal->matakuliah->kode_mk }}</p>
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="material-icons text-xs me-1">schedule</i>
                                                        <span class="text-xs font-weight-bold">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="material-icons text-xs me-1">room</i>
                                                        <span class="text-xs">{{ $jadwal->ruang->nama_ruang }}</span>
                                                    </div>
                                                    @if($jadwal->matakuliah->pengampu->count() > 0)
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="material-icons text-xs me-1">person</i>
                                                            <span class="text-xs">{{ $jadwal->matakuliah->pengampu->first()->nama }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span class="badge badge-sm bg-gradient-{{ $dayColors[$day] }}">{{ $jadwal->matakuliah->sks }} SKS</span>
                                                        <span class="text-xs text-muted">{{ $jadwal->semester_tahun }}</span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="material-icons text-muted" style="font-size: 48px;"> </i>
                                                    <p class="text-muted text-sm mt-2">Tidak ada jadwal</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row px-3 mt-4">
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="icon icon-lg bg-gradient-primary text-white border-radius-md mb-2">
                                            <i class="material-icons"></i>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $totalJadwal }}</h5>
                                        <p class="text-sm text-muted mb-0">Total Jadwal</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="icon icon-lg bg-gradient-success text-white border-radius-md mb-2">
                                            <i class="material-icons"></i>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $totalMatakuliah }}</h5>
                                        <p class="text-sm text-muted mb-0">Mata Kuliah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="icon icon-lg bg-gradient-info text-white border-radius-md mb-2">
                                            <i class="material-icons"></i>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $totalRuang }}</h5>
                                        <p class="text-sm text-muted mb-0">Ruang Digunakan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="icon icon-lg bg-gradient-warning text-white border-radius-md mb-2">
                                            <i class="material-icons"></i>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $totalDosen }}</h5>
                                        <p class="text-sm text-muted mb-0">Dosen Mengajar</p>
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

<style>
.schedule-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.schedule-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .schedule-item h6 {
        font-size: 0.75rem;
    }
    
    .schedule-item .text-xs {
        font-size: 0.65rem;
    }
}
</style>
@endsection