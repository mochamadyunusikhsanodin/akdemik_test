@extends('layouts.admin')

@section('page-title', 'Detail User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Detail User: {{ $user->name }}</h6>
                        <div class="me-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-white me-2">
                                <i class="material-icons text-sm">edit</i> Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-white">
                                <i class="material-icons text-sm">arrow_back</i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Nama Lengkap</label>
                            <p class="form-control-static">{{ $user->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Email</label>
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Role</label>
                            <p class="form-control-static">
                                <span class="badge badge-sm bg-gradient-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'dosen' ? 'success' : 'info') }}">
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">NIM/NIP</label>
                            <p class="form-control-static">{{ $user->nim_nip ?? '-' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">No. Telepon</label>
                            <p class="form-control-static">{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Fakultas</label>
                            <p class="form-control-static">{{ $user->fakultas ?? '-' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Jurusan</label>
                            <p class="form-control-static">{{ $user->jurusan ?? '-' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Semester</label>
                            <p class="form-control-static">{{ $user->semester ?? '-' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Lokasi</label>
                            <p class="form-control-static">{{ $user->location ?? '-' }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Tanggal Dibuat</label>
                            <p class="form-control-static">{{ $user->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($user->about)
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-sm font-weight-bold">Tentang</label>
                            <p class="form-control-static">{{ $user->about }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
                        <i class="material-icons text-sm">edit</i> Edit User
                    </a>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="material-icons text-sm">delete</i> Hapus User
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection