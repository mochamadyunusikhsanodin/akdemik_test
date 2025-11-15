@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Card Progress KRS -->
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Progress SKS</p>
                        <button class="btn btn-primary btn-sm ms-auto">{{ $totalSks }}/{{ $maxSks }} SKS</button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total SKS Diambil</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalSks }} dari {{ $maxSks }} SKS
                                    @if($totalSks >= $maxSks)
                                        <span class="text-danger text-sm">(Maksimal)</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-books text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Progress Bar SKS -->
                    <div class="progress mt-2">
                        <div class="progress-bar bg-gradient-primary" role="progressbar" 
                             style="width: {{ ($totalSks / $maxSks) * 100 }}%" 
                             aria-valuenow="{{ $totalSks }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $maxSks }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Progress Mata Kuliah</p>
                        <button class="btn btn-info btn-sm ms-auto">{{ $totalMatakuliah }}/{{ $maxMatakuliah }} MK</button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Mata Kuliah</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ $totalMatakuliah }} dari {{ $maxMatakuliah }} MK
                                    @if($totalMatakuliah >= $maxMatakuliah)
                                        <span class="text-danger text-sm">(Maksimal)</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-collection text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Progress Bar Mata Kuliah -->
                    <div class="progress mt-2">
                        <div class="progress-bar bg-gradient-info" role="progressbar" 
                             style="width: {{ ($totalMatakuliah / $maxMatakuliah) * 100 }}%" 
                             aria-valuenow="{{ $totalMatakuliah }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $maxMatakuliah }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alert jika mendekati limit -->
    @if($totalSks >= $maxSks * 0.8 || $totalMatakuliah >= $maxMatakuliah * 0.8)
        <div class="alert alert-warning" role="alert">
            <strong>Perhatian!</strong> 
            @if($totalSks >= $maxSks)
                Anda telah mencapai batas maksimal SKS ({{ $maxSks }} SKS).
            @elseif($totalSks >= $maxSks * 0.8)
                Anda mendekati batas maksimal SKS. Sisa: {{ $maxSks - $totalSks }} SKS.
            @endif
            
            @if($totalMatakuliah >= $maxMatakuliah)
                Anda telah mencapai batas maksimal mata kuliah ({{ $maxMatakuliah }} MK).
            @elseif($totalMatakuliah >= $maxMatakuliah * 0.8)
                Anda mendekati batas maksimal mata kuliah. Sisa: {{ $maxMatakuliah - $totalMatakuliah }} MK.
            @endif
        </div>
    @endif
    
    <!-- Tabel KRS yang sudah ada -->
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Kartu Rencana Studi (KRS)</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode MK</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mata Kuliah</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SKS</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hari</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jam</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ruang</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-secondary opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($krsData as $krs)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $krs->jadwal->matakuliah->kode_mk }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $krs->jadwal->matakuliah->nama_mk }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">{{ $krs->jadwal->matakuliah->sks }} SKS</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $krs->jadwal->hari }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $krs->jadwal->jam_mulai }} - {{ $krs->jadwal->jam_selesai }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $krs->jadwal->ruang->nama_ruang }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($krs->status == 'approved')
                                            <span class="badge badge-sm bg-gradient-success">Disetujui</span>
                                        @elseif($krs->status == 'pending')
                                            <span class="badge badge-sm bg-gradient-warning">Menunggu</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($krs->status == 'pending')
                                        <form action="{{ route('mahasiswa.krs.remove', $krs->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="material-icons text-sm me-2">delete</i>Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <p class="text-muted">Belum ada mata kuliah yang diambil</p>
                                        <a href="{{ route('mahasiswa.jadwal-tersedia') }}" class="btn btn-primary btn-sm">
                                            <i class="material-icons text-sm">add</i> Tambah Mata Kuliah
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection