@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Detail Ruang</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">ID Ruang:</label>
                                <p class="form-control-static">{{ $ruang->id_ruang }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Nama Ruang:</label>
                                <p class="form-control-static">{{ $ruang->nama_ruang }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Dibuat:</label>
                                <p class="form-control-static">{{ $ruang->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-dark font-weight-bold">Diupdate:</label>
                                <p class="form-control-static">{{ $ruang->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.ruang.edit', $ruang->id_ruang) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route('admin.ruang.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection