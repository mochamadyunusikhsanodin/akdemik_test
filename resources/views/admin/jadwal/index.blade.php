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
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3 mb-0">Tabel Jadwal</h6>
                                <div class="me-3">
                                    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-light mb-0">
                                        <i class="material-icons text-sm">Tambah Jadwal</i>&nbsp;&nbsp;
                                    </a>
                                    <a href="{{ route('admin.jadwal.calendar') }}" class="btn btn-sm btn-info mb-0">
                                        <i class="material-icons text-sm">Kalender</i>&nbsp;&nbsp;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <!-- Filter dan Search -->
                        <div class="row px-3 mb-3">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.jadwal.index') }}">
                                    <div class="input-group input-group-outline">
                                        <label class="form-label">Cari jadwal...</label>
                                        <input type="text" name="search" class="form-control" value="{{ $search }}">
                                        <input type="hidden" name="semester" value="{{ $semester_filter }}">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.jadwal.index') }}">
                                    <div class="input-group input-group-outline">
                                        <select name="semester" class="form-control" onchange="this.form.submit()">
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester }}" {{ $semester_filter == $semester ? 'selected' : '' }}>
                                                    {{ $semester }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="search" value="{{ $search }}">
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success mx-3">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mata Kuliah</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dosen</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ruang</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hari</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Semester</th>
                                        <th class="text-secondary opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwals as $jadwal)
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
                                            @if($jadwal->matakuliah->pengampu->count() > 0)
                                                @foreach($jadwal->matakuliah->pengampu as $dosen)
                                                    <span class="text-secondary text-xs font-weight-bold">{{ $dosen->nama }}</span><br>
                                                    <span class="text-xs text-secondary">{{ $dosen->nip }}</span>
                                                    @if(!$loop->last)<br>@endif
                                                @endforeach
                                            @else
                                                <span class="text-xs text-secondary">Belum ada pengampu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $jadwal->ruang->nama_ruang }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $jadwal->id_ruang }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm bg-gradient-info">{{ $jadwal->hari }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $jadwal->semester_tahun }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('admin.jadwal.show', $jadwal) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Lihat">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('admin.jadwal.edit', $jadwal) }}" class="text-secondary font-weight-bold text-xs mx-2" data-toggle="tooltip" data-original-title="Edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="border-0 bg-transparent text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Hapus">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-secondary mb-0">Tidak ada data jadwal</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($jadwals->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $jadwals->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection