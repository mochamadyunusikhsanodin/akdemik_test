@extends('layouts.app')

@section('page-title', 'Manajemen User')

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
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Daftar Pengguna</h6>
                        <div class="me-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-outline-white">
                                <i class="material-icons text-sm">Tambah User</i> 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body px-0 pb-2">
                <!-- Filter dan Search -->
                <div class="row px-3 mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
                            <div class="input-group input-group-outline me-2">
                                <label class="form-label">Cari pengguna</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm mb-0">Cari</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex justify-content-end">
                            <div class="input-group input-group-outline me-2" style="max-width: 200px;">
                                <select name="role" class="form-control" onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="dosen" {{ request('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="mahasiswa" {{ request('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                </select>
                            </div>
                            @if(request('search') || request('role'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-0">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengguna</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM/NIP</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fakultas</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Dibuat</th>
                                <th class="text-secondary opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'dosen' ? 'success' : 'info') }}">{{ $user->getRoleDisplayName() }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">{{ $user->nim_nip ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">{{ $user->fakultas ?? '-' }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Aksi
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">Lihat</a></li>
                                            <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">Edit</a></li>
                                            @if($user->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-sm text-secondary mb-0">Tidak ada pengguna ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</main>
@endsection