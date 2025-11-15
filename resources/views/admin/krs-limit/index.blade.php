@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Pengaturan Limit KRS</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger mx-3" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.krs-limit.update') }}" method="POST" class="mx-3">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Maksimal SKS per Semester</label>
                                    <input type="number" class="form-control" name="max_sks" value="{{ old('max_sks', $maxSks) }}" min="1" max="30" required>
                                </div>
                                <small class="text-muted">Batas maksimal total SKS yang dapat diambil mahasiswa per semester</small>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Maksimal Mata Kuliah per Semester</label>
                                    <input type="number" class="form-control" name="max_matakuliah" value="{{ old('max_matakuliah', $maxMatakuliah) }}" min="1" max="15" required>
                                </div>
                                <small class="text-muted">Batas maksimal jumlah mata kuliah yang dapat diambil mahasiswa per semester</small>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection