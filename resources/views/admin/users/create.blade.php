@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Tambah User Baru</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form action="{{ route('admin.users.store') }}" method="POST" class="px-4">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email</label>
                                    <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-control-label">Password</label>
                                    <input class="form-control" type="password" name="password" id="password" required>
                                    @error('password')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-control-label">Konfirmasi Password</label>
                                    <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-control-label">Pilih Role</label>
                                    <select class="form-control" name="role" id="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                        <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    </select>
                                    @error('role')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nim_nip" class="form-control-label">NIM/NIP</label>
                                    <input class="form-control" type="text" name="nim_nip" id="nim_nip" value="{{ old('nim_nip') }}">
                                    <small class="text-muted">Wajib diisi untuk role Dosen dan Mahasiswa</small>
                                    @error('nim_nip')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Field Golongan untuk Mahasiswa -->
                        <div class="row" id="golongan-field" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_gol" class="form-control-label">Golongan</label>
                                    <select class="form-control" name="id_gol" id="id_gol">
                                        <option value="">Pilih Golongan</option>
                                        @foreach($golongans as $golongan)
                                            <option value="{{ $golongan->id_gol }}" {{ old('id_gol') == $golongan->id_gol ? 'selected' : '' }}>
                                                {{ $golongan->id_gol }} - {{ $golongan->nama_gol }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_gol')
                                        <div class="text-danger text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan User</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const golonganField = document.getElementById('golongan-field');
    const golonganSelect = document.getElementById('id_gol');
    
    function toggleGolonganField() {
        if (roleSelect.value === 'mahasiswa') {
            golonganField.style.display = 'block';
            golonganSelect.required = true;
        } else {
            golonganField.style.display = 'none';
            golonganSelect.required = false;
            golonganSelect.value = '';
        }
    }
    
    roleSelect.addEventListener('change', toggleGolonganField);
    
    // Check initial state
    toggleGolonganField();
});
</script>
@endsection