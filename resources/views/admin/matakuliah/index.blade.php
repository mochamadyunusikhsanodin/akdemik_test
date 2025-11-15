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
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Data Mata Kuliah</h5>
                                <p class="text-sm mb-0">Kelola data mata kuliah</p>
                            </div>
                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route('admin.matakuliah.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Tambah Mata Kuliah</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search Form -->
                    <div class="card-body px-0 pb-0">
                        <div class="row px-4">
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('admin.matakuliah.index') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Cari mata kuliah..." value="{{ $search }}">
                                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                                        @if($search)
                                            <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-outline-secondary">Reset</a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0 pb-2">
                        @if(session('success'))
                            <div class="alert alert-success mx-4">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger mx-4">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode MK</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Mata Kuliah</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKS</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Semester</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($matakuliah as $mk)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $mk->kode_mk }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $mk->nama_mk }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm bg-gradient-info">{{ $mk->sks }} SKS</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm bg-gradient-secondary">Semester {{ $mk->semester }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('admin.matakuliah.show', $mk->kode_mk) }}" class="text-secondary font-weight-bold text-xs me-2" data-toggle="tooltip" data-original-title="Lihat detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.matakuliah.edit', $mk->kode_mk) }}" class="text-secondary font-weight-bold text-xs me-2" data-toggle="tooltip" data-original-title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.matakuliah.destroy', $mk->kode_mk) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="border-0 bg-transparent text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-sm mb-0">Tidak ada data mata kuliah.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($matakuliah->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $matakuliah->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</main>
@endsection