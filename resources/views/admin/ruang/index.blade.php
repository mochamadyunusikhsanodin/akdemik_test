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
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Manajemen Ruang</h6>
                            <div class="me-3">
                                <a href="{{ route('admin.ruang.create') }}" class="btn btn-outline-white btn-sm mb-0">
                                    <i class="fas fa-plus me-2"></i>Tambah Ruang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mx-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- Search Form -->
                    <div class="row mx-4 mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.ruang.index') }}" class="d-flex">
                                <div class="input-group input-group-outline me-2">
                                    <input type="text" name="search" class="form-control" placeholder="Cari ruang..." value="{{ request('search') }}">
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-sm mb-0">Cari</button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            @if(request('search'))
                                <a href="{{ route('admin.ruang.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Reset</a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="table-responsive p-0 mx-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Ruang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Ruang</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dibuat</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ruangs as $index => $ruang)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $ruangs->firstItem() + $index }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $ruang->id_ruang }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $ruang->nama_ruang }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $ruang->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('admin.ruang.show', $ruang->id_ruang) }}" class="text-info font-weight-bold text-xs me-2" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ruang.edit', $ruang->id_ruang) }}" class="text-warning font-weight-bold text-xs me-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ruang.destroy', $ruang->id_ruang) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-0 bg-transparent text-danger font-weight-bold text-xs" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <p class="text-xs text-secondary mb-0">Tidak ada data ruang.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $ruangs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection