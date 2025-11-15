@extends('layouts.app')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="top-0 bg-cover z-index-n1 min-height-100 max-height-200 h-25 position-absolute w-100 start-0 end-0"
         style="background-image: url('{{ asset('assets/img/header-blue-purple.jpg') }}'); background-position: bottom;">
    </div>
    <x-app.navbar />
    <div class="px-5 py-4 container-fluid">
        <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Profile Header -->
            <div class="mt-5 mb-5 mt-lg-9 row justify-content-center">
                <div class="col-lg-9 col-12">
                    <div class="card card-body" id="profile">
                        <img src="{{ asset('assets/img/header-orange-purple.jpg') }}" alt="pattern-lines"
                             class="top-0 rounded-2 position-absolute start-0 w-100 h-100">
                        
                        <div class="row z-index-2 justify-content-center align-items-center">
                            <div class="col-sm-auto col-4">
                                <div class="avatar avatar-xl position-relative">
                                    <img src="{{ $user->avatar_url }}"
                                         alt="{{ $user->name }}"
                                         class="w-100 h-100 object-fit-cover border-radius-lg shadow-sm"
                                         id="preview">
                                    <div class="position-absolute bottom-0 end-0">
                                        <button type="button" class="btn btn-sm btn-icon-only bg-gradient-light mb-0" 
                                                onclick="document.getElementById('avatar').click()">
                                            <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Image"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto col-8 my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1 font-weight-bolder text-white">
                                        {{ $user->name }}
                                    </h5>
                                    <p class="mb-0 font-weight-bold text-sm text-white">
                                        Mahasiswa - NIM: {{ $mahasiswa->nim }}
                                    </p>
                                    <p class="mb-0 text-sm text-white">
                                        Semester {{ $mahasiswa->semester }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-9 col-12">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3 class="text-primary">{{ $stats['total_krs'] }}</h3>
                                    <p class="mb-0">Total KRS</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3 class="text-success">{{ $stats['krs_approved'] }}</h3>
                                    <p class="mb-0">KRS Disetujui</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3 class="text-info">{{ $stats['semester_aktif'] }}</h3>
                                    <p class="mb-0">Semester Aktif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            <div class="row justify-content-center">
                <div class="col-lg-9 col-12">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Basic Info -->
            <div class="mb-5 row justify-content-center">
                <div class="col-lg-9 col-12">
                    <div class="card" id="basic-info">
                        <div class="card-header">
                            <h5>Informasi Dasar</h5>
                        </div>
                        <div class="pt-0 card-body">
                            <!-- Hidden file input for avatar -->
                            <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;" onchange="previewImage(this)">
                            @error('avatar')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            <div class="row">
                                <div class="col-6">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" name="name" id="name"
                                           value="{{ old('name', $user->name) }}" class="form-control">
                                    @error('name')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email"
                                           value="{{ old('email', $user->email) }}" class="form-control">
                                    @error('email')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <label for="phone">No. HP (User)</label>
                                    <input type="text" name="phone" id="phone"
                                           value="{{ old('phone', $user->phone) }}" class="form-control">
                                    @error('phone')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="no_hp">No. HP (Mahasiswa)</label>
                                    <input type="text" name="no_hp" id="no_hp"
                                           value="{{ old('no_hp', $mahasiswa->no_hp) }}" class="form-control">
                                    @error('no_hp')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-4">
                                    <label for="location">Lokasi (User)</label>
                                    <input type="text" name="location" id="location"
                                           value="{{ old('location', $user->location) }}" class="form-control">
                                    @error('location')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-4">
                                    <label for="alamat">Alamat (Mahasiswa)</label>
                                    <input type="text" name="alamat" id="alamat"
                                           value="{{ old('alamat', $mahasiswa->alamat) }}" class="form-control">
                                    @error('alamat')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-4">
                                    <label for="semester">Semester</label>
                                    <select name="semester" id="semester" class="form-control">
                                        @for($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}" {{ old('semester', $mahasiswa->semester) == $i ? 'selected' : '' }}>
                                                Semester {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('semester')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row p-2">
                                <label for="about">Tentang Saya</label>
                                <textarea name="about" id="about" rows="5" class="form-control">{{ old('about', $user->about) }}</textarea>
                                @error('about')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="mt-6 mb-0 btn btn-primary btn-sm float-end">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mata Kuliah yang Diambil -->
            <div class="mb-5 row justify-content-center">
                <div class="col-lg-9 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Mata Kuliah yang Diambil</h5>
                        </div>
                        <div class="card-body">
                            @if($stats['mata_kuliah_diambil']->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode MK</th>
                                                <th>Nama Mata Kuliah</th>
                                                <th>SKS</th>
                                                <th>Hari</th>
                                                <th>Jam</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['mata_kuliah_diambil'] as $krs)
                                                <tr>
                                                    <td>{{ $krs->jadwal->matakuliah->kode_mk }}</td>
                                                    <td>{{ $krs->jadwal->matakuliah->nama_mk }}</td>
                                                    <td>{{ $krs->jadwal->matakuliah->sks }}</td>
                                                    <td>{{ $krs->jadwal->hari }}</td>
                                                    <td>{{ $krs->jadwal->jam_mulai }} - {{ $krs->jadwal->jam_selesai }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $krs->status == 'approved' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($krs->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada mata kuliah yang diambil.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <x-app.footer />
</main>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection