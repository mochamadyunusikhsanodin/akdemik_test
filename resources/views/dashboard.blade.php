<x-app-layout>
    <x-slot name="slot">
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            <x-app.navbar />
            <div class="container-fluid py-4 px-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-md-flex align-items-center mb-3 mx-2">
                            <div class="mb-md-0 mb-3">
                                <h3 class="font-weight-bold mb-0">Selamat Datang, {{ Auth::user()->name }}</h3>
                                <p class="mb-0">Sistem Informasi Akademik - Dashboard Utama</p>
                            </div>
                            
                            @if(auth()->user() && auth()->user()->role === 'admin')
                            <div class="ms-auto d-flex gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-cog me-1"></i>
                                    Admin Panel
                                </a>
                                <button type="button" class="btn btn-sm btn-white btn-icon d-flex align-items-center mb-0 me-2">
                                    <span class="btn-inner--icon">
                                        <span class="p-1 bg-success rounded-circle d-flex ms-auto me-2">
                                            <span class="visually-hidden">New</span>
                                        </span>
                                    </span>
                                    <span class="btn-inner--text">Notifikasi</span>
                                </button>
                            </div>
                            @else
                            <button type="button" class="btn btn-sm btn-white btn-icon d-flex align-items-center mb-0 ms-md-auto mb-sm-0 mb-2 me-2">
                                <span class="btn-inner--icon">
                                    <span class="p-1 bg-success rounded-circle d-flex ms-auto me-2">
                                        <span class="visually-hidden">New</span>
                                    </span>
                                </span>
                                <span class="btn-inner--text">Notifikasi</span>
                            </button>
                            @endif
                            
                            <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
                                <span class="btn-inner--icon">
                                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-block me-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                </span>
                                <span class="btn-inner--text">Sinkronisasi</span>
                            </button>
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
                                <div class="swiper-slide">
                                    <div>
                                        <div class="card card-background shadow-none border-radius-xl card-background-after-none align-items-start mb-0">
                                            <div class="full-background bg-cover" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)"></div>
                                            <div class="card-body text-start px-3 py-0 w-100">
                                                <div class="row mt-12">
                                                    <div class="col-sm-3 mt-auto">
                                                        <h4 class="text-white font-weight-bolder">#1</h4>
                                                        <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Modul</p>
                                                        <h5 class="text-white font-weight-bolder">Data Dosen</h5>
                                                    </div>
                                                    <div class="col-sm-3 ms-auto mt-auto">
                                                        <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Kategori</p>
                                                        <h5 class="text-white font-weight-bolder">SDM Akademik</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Data Mahasiswa -->
                                <div class="swiper-slide">
                                    <div class="card card-background shadow-none border-radius-xl card-background-after-none align-items-start mb-0">
                                        <div class="full-background bg-cover" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"></div>
                                        <div class="card-body text-start px-3 py-0 w-100">
                                            <div class="row mt-12">
                                                <div class="col-sm-3 mt-auto">
                                                    <h4 class="text-white font-weight-bolder">#2</h4>
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Modul</p>
                                                    <h5 class="text-white font-weight-bolder">Data Mahasiswa</h5>
                                                </div>
                                                <div class="col-sm-3 ms-auto mt-auto">
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Kategori</p>
                                                    <h5 class="text-white font-weight-bolder">Kemahasiswaan</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Data Kurikulum -->
                                <div class="swiper-slide">
                                    <div class="card card-background shadow-none border-radius-xl card-background-after-none align-items-start mb-0">
                                        <div class="full-background bg-cover" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"></div>
                                        <div class="card-body text-start px-3 py-0 w-100">
                                            <div class="row mt-12">
                                                <div class="col-sm-3 mt-auto">
                                                    <h4 class="text-white font-weight-bolder">#3</h4>
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Modul</p>
                                                    <h5 class="text-white font-weight-bolder">Kurikulum</h5>
                                                </div>
                                                <div class="col-sm-3 ms-auto mt-auto">
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Kategori</p>
                                                    <h5 class="text-white font-weight-bolder">Mata Kuliah</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Penjadwalan Akademik -->
                                <div class="swiper-slide">
                                    <div class="card card-background shadow-none border-radius-xl card-background-after-none align-items-start mb-0">
                                        <div class="full-background bg-cover" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)"></div>
                                        <div class="card-body text-start px-3 py-0 w-100">
                                            <div class="row mt-12">
                                                <div class="col-sm-3 mt-auto">
                                                    <h4 class="text-white font-weight-bolder">#4</h4>
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Modul</p>
                                                    <h5 class="text-white font-weight-bolder">Penjadwalan</h5>
                                                </div>
                                                <div class="col-sm-3 ms-auto mt-auto">
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Kategori</p>
                                                    <h5 class="text-white font-weight-bolder">Jadwal Kuliah</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Proses Akademik -->
                                <div class="swiper-slide">
                                    <div class="card card-background shadow-none border-radius-xl card-background-after-none align-items-start mb-0">
                                        <div class="full-background bg-cover" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%)"></div>
                                        <div class="card-body text-start px-3 py-0 w-100">
                                            <div class="row mt-12">
                                                <div class="col-sm-3 mt-auto">
                                                    <h4 class="text-white font-weight-bolder">#5</h4>
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Modul</p>
                                                    <h5 class="text-white font-weight-bolder">Proses Akademik</h5>
                                                </div>
                                                <div class="col-sm-3 ms-auto mt-auto">
                                                    <p class="text-white opacity-8 text-xs font-weight-bolder mb-0">Kategori</p>
                                                    <h5 class="text-white font-weight-bolder">Presensi</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                
                <!-- Statistik Akademik -->
                <div class="row my-4">
                    <div class="col-lg-4 col-md-6 mb-md-0 mb-4">
                        <div class="card shadow-xs border h-100">
                            <div class="card-header pb-0">
                                <h6 class="font-weight-semibold text-lg mb-0">Statistik Semester</h6>
                                <p class="text-sm">Data statistik akademik per semester.</p>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                                    <label class="btn btn-white px-3 mb-0" for="btnradio1">Tahun Ini</label>
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                                    <label class="btn btn-white px-3 mb-0" for="btnradio2">Semester</label>
                                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                                    <label class="btn btn-white px-3 mb-0" for="btnradio3">Bulan</label>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                <div class="chart mb-2">
                                    <canvas id="chart-bars" class="chart-canvas" height="240"></canvas>
                                </div>
                                <button class="btn btn-white mb-0 ms-auto">Lihat Laporan</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-8 col-md-6">
                        <div class="card shadow-xs border">
                            <div class="card-header border-bottom pb-0">
                                <div class="d-sm-flex align-items-center mb-3">
                                    <div>
                                        <h6 class="font-weight-semibold text-lg mb-0">Aktivitas Akademik Terkini</h6>
                                        <p class="text-sm mb-sm-0 mb-2">Data aktivitas akademik terbaru dalam sistem</p>
                                    </div>
                                    <div class="ms-auto d-flex">
                                        <button type="button" class="btn btn-sm btn-white mb-0 me-2">
                                            Lihat Semua
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center mb-0">
                                            <span class="btn-inner--icon">
                                                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="d-block me-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                </svg>
                                            </span>
                                            <span class="btn-inner--text">Export</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="pb-3 d-sm-flex align-items-center">
                                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable1" autocomplete="off" checked>
                                        <label class="btn btn-white px-3 mb-0" for="btnradiotable1">Semua</label>
                                        <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable2" autocomplete="off">
                                        <label class="btn btn-white px-3 mb-0" for="btnradiotable2">Mahasiswa</label>
                                        <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable3" autocomplete="off">
                                        <label class="btn btn-white px-3 mb-0" for="btnradiotable3">Dosen</label>
                                    </div>
                                    <div class="input-group w-sm-25 ms-auto">
                                        <span class="input-group-text text-body">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                            </svg>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Cari aktivitas...">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 py-0">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center justify-content-center mb-0">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7">Aktivitas</th>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Pengguna</th>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Waktu</th>
                                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Status</th>
                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2">
                                                        <div class="avatar avatar-sm rounded-circle bg-gradient-primary me-2 my-2">
                                                            <i class="fas fa-user-graduate text-white"></i>
                                                        </div>
                                                        <div class="my-auto">
                                                            <h6 class="mb-0 text-sm">Pendaftaran Mahasiswa Baru</h6>
                                                            <p class="text-xs text-secondary mb-0">Mahasiswa baru mendaftar</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0">Ahmad Rizki</p>
                                                    <p class="text-xs text-secondary mb-0">NIM: 2024001</p>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">Hari ini 14:30</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-success">Aktif</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2">
                                                        <div class="avatar avatar-sm rounded-circle bg-gradient-info me-2 my-2">
                                                            <i class="fas fa-chalkboard-teacher text-white"></i>
                                                        </div>
                                                        <div class="my-auto">
                                                            <h6 class="mb-0 text-sm">Input Presensi Kuliah</h6>
                                                            <p class="text-xs text-secondary mb-0">Dosen mengisi presensi</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0">Dr. Siti Aminah</p>
                                                    <p class="text-xs text-secondary mb-0">NIP: 198501012010</p>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">Hari ini 13:15</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-info">Selesai</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2">
                                                        <div class="avatar avatar-sm rounded-circle bg-gradient-warning me-2 my-2">
                                                            <i class="fas fa-calendar-alt text-white"></i>
                                                        </div>
                                                        <div class="my-auto">
                                                            <h6 class="mb-0 text-sm">Update Jadwal Kuliah</h6>
                                                            <p class="text-xs text-secondary mb-0">Perubahan jadwal mata kuliah</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0">Admin Akademik</p>
                                                    <p class="text-xs text-secondary mb-0">Bagian Akademik</p>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-normal">Kemarin 16:45</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats Cards -->
                <div class="row mt-4">
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Dosen</p>
                                            <h5 class="font-weight-bolder mb-0">156</h5>
                                            <span class="text-success text-sm font-weight-bolder">+5% dari bulan lalu</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="fas fa-chalkboard-teacher text-lg opacity-10" aria-hidden="true"></i>
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
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Mahasiswa</p>
                                            <h5 class="font-weight-bolder mb-0">2,847</h5>
                                            <span class="text-success text-sm font-weight-bolder">+12% dari tahun lalu</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                            <i class="fas fa-user-graduate text-lg opacity-10" aria-hidden="true"></i>
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
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Mata Kuliah</p>
                                            <h5 class="font-weight-bolder mb-0">89</h5>
                                            <span class="text-warning text-sm font-weight-bolder">Semester ini</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                            <i class="fas fa-book text-lg opacity-10" aria-hidden="true"></i>
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
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Kelas Aktif</p>
                                            <h5 class="font-weight-bolder mb-0">124</h5>
                                            <span class="text-info text-sm font-weight-bolder">Semester berjalan</span>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                            <i class="fas fa-calendar-check text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
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
