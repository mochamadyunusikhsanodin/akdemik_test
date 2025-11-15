@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Tambah Ruang Baru</h6>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ruang.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3 @error('id_ruang') is-invalid @enderror">
                                    <label class="form-label">ID Ruang</label>
                                    <input type="text" name="id_ruang" class="form-control" value="{{ old('id_ruang') }}" maxlength="10">
                                </div>
                                @error('id_ruang')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3 @error('nama_ruang') is-invalid @enderror">
                                    <label class="form-label">Nama Ruang</label>
                                    <input type="text" name="nama_ruang" class="form-control" value="{{ old('nama_ruang') }}" maxlength="50">
                                </div>
                                @error('nama_ruang')
                                    <div class="text-danger text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.ruang.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection