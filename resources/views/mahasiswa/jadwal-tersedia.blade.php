@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <!-- Info Box Limit -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="mb-0">SKS Diambil</h6>
                                <h4 class="font-weight-bolder {{ $totalSks >= $maxSks ? 'text-danger' : 'text-primary' }}">
                                    {{ $totalSks }}/{{ $maxSks }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="mb-0">Mata Kuliah</h6>
                                <h4 class="font-weight-bolder {{ $totalMatakuliah >= $maxMatakuliah ? 'text-danger' : 'text-info' }}">
                                    {{ $totalMatakuliah }}/{{ $maxMatakuliah }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="mb-0">Sisa SKS</h6>
                                <h4 class="font-weight-bolder text-success">
                                    {{ max(0, $maxSks - $totalSks) }}
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h6 class="mb-0">Sisa Slot MK</h6>
                                <h4 class="font-weight-bolder text-success">
                                    {{ max(0, $maxMatakuliah - $totalMatakuliah) }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Jadwal dengan indikator limit -->
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Jadwal Tersedia</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Dosen</th>
                                    <th>Jadwal</th>
                                    <th>Ruang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwals as $jadwal)
                                    @php
                                        $sksJadwal = $jadwal->matakuliah->sks ?? 0;
                                        $canTakeSks = ($totalSks + $sksJadwal) <= $maxSks;
                                        $canTakeMk = $totalMatakuliah < $maxMatakuliah;
                                        $canTake = $canTakeSks && $canTakeMk;
                                        $alreadyTaken = $takenKrs->contains('jadwal_id', $jadwal->id);
                                    @endphp
                                    <tr class="{{ !$canTake || $alreadyTaken ? 'table-secondary' : '' }}">
                                        <td>{{ $jadwal->matakuliah->nama_mk }}</td>
                                        <td>
                                            <span class="badge badge-sm {{ $canTakeSks ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                                {{ $sksJadwal }} SKS
                                            </span>
                                        </td>
                                        <td>{{ $jadwal->matakuliah->pengampu->first()->nama ?? 'Belum ditentukan' }}</td>
                                        <td>{{ $jadwal->hari }}, {{ $jadwal->jam_mulai }}-{{ $jadwal->jam_selesai }}</td>
                                        <td>{{ $jadwal->ruang->nama_ruang }}</td>
                                        <td>
                                            @if($alreadyTaken)
                                                <span class="badge bg-gradient-info">Sudah Diambil</span>
                                            @elseif(!$canTake)
                                                @if(!$canTakeSks)
                                                    <span class="badge bg-gradient-danger">Melebihi Limit SKS</span>
                                                @elseif(!$canTakeMk)
                                                    <span class="badge bg-gradient-danger">Melebihi Limit MK</span>
                                                @endif
                                            @else
                                                <form action="{{ route('mahasiswa.krs.add') }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                                                    <button type="submit" class="btn btn-primary btn-sm">Ambil</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[action*="krs.add"]');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const currentSks = {{ $totalSks }};
                const maxSks = {{ $maxSks }};
                const currentMk = {{ $totalMatakuliah }};
                const maxMk = {{ $maxMatakuliah }};
                
                const jadwalRow = this.closest('tr');
                const sksText = jadwalRow.querySelector('td:nth-child(2)').textContent;
                const sks = parseInt(sksText.match(/\d+/)[0]);
                const mkName = jadwalRow.querySelector('td:first-child').textContent;
                
                const newTotalSks = currentSks + sks;
                const newTotalMk = currentMk + 1;
                
                let message = `Anda akan mengambil mata kuliah: ${mkName} (${sks} SKS)\n\n`;
                message += `Total SKS setelah diambil: ${newTotalSks}/${maxSks}\n`;
                message += `Total Mata Kuliah setelah diambil: ${newTotalMk}/${maxMk}\n\n`;
                
                if (newTotalSks >= maxSks * 0.9) {
                    message += 'Peringatan: Anda mendekati batas maksimal SKS!\n';
                }
                
                if (newTotalMk >= maxMk * 0.9) {
                    message += 'Peringatan: Anda mendekati batas maksimal mata kuliah!\n';
                }
                
                message += '\nLanjutkan mengambil mata kuliah ini?';
                
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush