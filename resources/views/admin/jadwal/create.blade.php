@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.jadwal.index') }}">Jadwal</a></li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tambah</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Tambah Jadwal</h6>
            </nav>
        </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Tambah Jadwal Baru</p>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.jadwal.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_mk" class="form-control-label">Mata Kuliah</label>
                                        <select class="form-control" name="kode_mk" id="kode_mk" required>
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach($matakuliah->groupBy('semester') as $semester => $mks)
                                                <optgroup label="Semester {{ $semester }}">
                                                    @foreach($mks as $mk)
                                                        <option value="{{ $mk->kode_mk }}" {{ old('kode_mk') == $mk->kode_mk ? 'selected' : '' }}>
                                                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nip" class="form-control-label">Dosen Pengampu</label>
                                        <select class="form-control" name="nip" id="nip" required>
                                            <option value="">Pilih Dosen</option>
                                            @foreach($dosen as $d)
                                                <option value="{{ $d->nip }}" {{ old('nip') == $d->nip ? 'selected' : '' }}>
                                                    {{ $d->nip }} - {{ $d->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_ruang" class="form-control-label">Ruang</label>
                                        <select class="form-control" name="id_ruang" id="id_ruang" required>
                                            <option value="">Pilih Ruang</option>
                                            @foreach($ruang as $r)
                                                <option value="{{ $r->id_ruang }}" {{ old('id_ruang') == $r->id_ruang ? 'selected' : '' }}>
                                                    {{ $r->id_ruang }} - {{ $r->nama_ruang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="semester_tahun" class="form-control-label">Semester/Tahun</label>
                                        <select class="form-control" name="semester_tahun" id="semester_tahun" required>
                                            <option value="">Pilih Semester/Tahun</option>
                                            @foreach($semesters as $semester)
                                                <option value="{{ $semester }}" {{ old('semester_tahun') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hari" class="form-control-label">Hari</label>
                                        <select class="form-control" name="hari" id="hari" required>
                                            <option value="">Pilih Hari</option>
                                            @foreach($hari as $h)
                                                <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jam_mulai" class="form-control-label">Jam Mulai</label>
                                        <input class="form-control" type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jam_selesai" class="form-control-label">Jam Selesai</label>
                                        <input class="form-control" type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" required>
                                    </div>
                                </div>
                            </div>
                            <!-- HAPUS DUPLIKASI SEMESTER/TAHUN DI SINI -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection