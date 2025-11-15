@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="slot">
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            <x-app.navbar />
            <div class="container-fluid py-4 px-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex align-items-center mb-3 mx-2">
                            <div class="mb-md-0 mb-3">
                                <h3 class="font-weight-bold mb-0">Selamat Datang, Administrator</h3>
                                <p class="mb-0">Admin Dashboard - Panel Administrasi Sistem</p>
                            </div>
                            
                            <div class="ms-auto d-flex gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-arrow-left me-1"></i>
                                    Kembali ke Dashboard
                                </a>
                                
                              
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                
                <!-- Menu Utama Sistem Akademik -->
                <div class="row">
                    <div class="position-relative overflow-hidden">
                        <div class="swiper mySwiper mt-4 mb-2">
                            <div class="swiper-wrapper">
                                <!-- Data Dosen -->
                              
                                
                               
                               
                                
                                <!-- Penjadwalan Akademik -->
                                
                                <!-- Proses Akademik -->
                             
                            </div>
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                
                <!-- Admin Statistics Cards -->
                <div class="row my-4">
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Users</p>
                                            <h5 class="font-weight-bolder mb-0">{{ $totalUsers ?? 0 }}</h5>
                                            <span class="text-success text-sm font-weight-bolder">Pengguna terdaftar</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Admin</p>
                                            <h5 class="font-weight-bolder mb-0">{{ $adminCount ?? 0 }}</h5>
                                            <span class="text-info text-sm font-weight-bolder">Administrator aktif</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                            <i class="fas fa-user-shield text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Dosen</p>
                                            <h5 class="font-weight-bolder mb-0">{{ $dosenCount ?? 0 }}</h5>
                                            <span class="text-warning text-sm font-weight-bolder">Dosen terdaftar</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                            <i class="fas fa-chalkboard-teacher text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Mahasiswa</p>
                                            <h5 class="font-weight-bolder mb-0">{{ $mahasiswaCount ?? 0 }}</h5>
                                            <span class="text-success text-sm font-weight-bolder">Mahasiswa aktif</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                            <i class="fas fa-user-graduate text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Users Table -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-xs border">
                            <div class="card-header border-bottom pb-0">
                                <div class="d-sm-flex align-items-center mb-3">
                                    <div>
                                        <h6 class="font-weight-semibold text-lg mb-0">Recent Users Table</h6>
                                        <p class="text-sm mb-sm-0 mb-2">Daftar pengguna yang baru mendaftar</p>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="card-body px-0 py-0">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center justify-content-center mb-0">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7">Nama</th>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Email</th>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Role</th>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Tanggal Daftar</th>
                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentUsers ?? [] as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2">
                                                        <div class="avatar avatar-sm rounded-circle bg-gradient-primary me-2 my-2">
                                                            <span class="text-white text-xs font-weight-bold">{{ substr($user->name, 0, 2) }}</span>
                                                        </div>
                                                        <div class="my-auto">
                                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0">{{ $user->email }}</p>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'dosen' ? 'info' : 'success') }}">{{ ucfirst($user->role) }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">{{ $user->created_at->format('d M Y') }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <p class="text-sm text-secondary mb-0">Belum ada data pengguna</p>
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
                
                <x-app.footer />
            </div>
        </main>
    </x-slot>
</x-app-layout>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 30,
            },
        },
    });
</script>
@endsection