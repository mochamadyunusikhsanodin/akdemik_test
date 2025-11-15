@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Tambah Golongan Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.golongan.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_gol" class="form-control-label">ID Golongan</label>
                                    <input class="form-control" type="text" name="id_gol" id="id_gol" value="{{ old('id_gol') }}" required>
                                    @error('id_gol')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_gol" class="form-control-label">Nama Golongan</label>
                                    <input class="form-control" type="text" name="nama_gol" id="nama_gol" value="{{ old('nama_gol') }}" required>
                                    @error('nama_gol')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan Golongan</button>
                            <a href="{{ route('admin.golongan.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection