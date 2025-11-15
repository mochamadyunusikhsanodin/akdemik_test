<?php


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/signin', [LoginController::class, 'showLoginForm'])->name('signin');
Route::post('/signin', [LoginController::class, 'login'])->name('signin.post');

// Guest routes (tidak memerlukan autentikasi)
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Protected routes (memerlukan autentikasi)
Route::group(['middleware' => 'auth'], function () {
    // Dashboard utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.main');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/activities', [DashboardController::class, 'getRecentActivities'])->name('dashboard.activities');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/user-profile', [ProfileController::class, 'show'])->name('user-profile');
    Route::post('/user-profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/users/profile', [ProfileController::class, 'show'])->name('users.profile');
    Route::put('/users/profile', [ProfileController::class, 'update'])->name('users.update');
    Route::delete('/user-profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');
    
    // Static pages
    Route::get('/billing', function () {
        return view('billing');
    })->name('billing');
    
    Route::get('/rtl', function () {
        return view('rtl');
    })->name('rtl');
    
    Route::get('/RTL', function () {
        return view('rtl');
    })->name('RTL');
    
    Route::get('/wallet', function () {
        return view('wallet');
    })->name('wallet');
    
    Route::get('/tables', function () {
        return view('tables');
    })->name('tables');
    
    Route::get('/virtual-reality', function () {
        return view('virtual-reality');
    })->name('virtual-reality');
    
    Route::get('/user-management', function () {
        return view('laravel-examples/user-management');
    })->name('user-management');
    
    Route::get('/users-management', function () {
        return view('laravel-examples/user-management');
    })->name('users-management');
    
    // Logout routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    
    // Golongan Management
    Route::resource('golongan', App\Http\Controllers\Admin\GolonganController::class);
    
    // Ruang Management
    Route::resource('ruang', App\Http\Controllers\Admin\RuangController::class);
    
    // Mata Kuliah Management
    Route::resource('matakuliah', App\Http\Controllers\Admin\MatakuliahController::class);
    
    // Jadwal Management
    Route::get('/jadwal/calendar', [App\Http\Controllers\Admin\JadwalController::class, 'calendar'])->name('jadwal.calendar');
    Route::resource('jadwal', App\Http\Controllers\Admin\JadwalController::class);
    
    // KRS Management - ADMIN SECTION
    Route::get('/krs', [App\Http\Controllers\Admin\KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs/toggle', [App\Http\Controllers\Admin\KrsController::class, 'toggle'])->name('krs.toggle');
    Route::get('/krs/settings', [App\Http\Controllers\Admin\KrsController::class, 'settings'])->name('krs.settings');
    Route::post('/krs/settings', [App\Http\Controllers\Admin\KrsController::class, 'updateSettings'])->name('krs.settings.update');
    
    // Route untuk pengaturan limit KRS (dalam group admin)
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // KRS Limit Settings
        Route::get('/krs-limit', [App\Http\Controllers\Admin\KrsLimitController::class, 'index'])->name('krs-limit.index');
        Route::put('/krs-limit', [App\Http\Controllers\Admin\KrsLimitController::class, 'update'])->name('krs-limit.update');
    });
});

// Dosen routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Dosen\DosenController::class, 'dashboard'])->name('dashboard');
    
    // Jadwal Mengajar
    Route::get('/jadwal', [App\Http\Controllers\Dosen\DosenController::class, 'jadwal'])->name('jadwal');
    
    // Presensi/Absensi
    Route::get('/presensi', [App\Http\Controllers\Dosen\DosenController::class, 'presensi'])->name('presensi');
    Route::get('/presensi/{jadwal_id}', [App\Http\Controllers\Dosen\DosenController::class, 'showPresensi'])->name('presensi.show');
    Route::post('/absensi', [App\Http\Controllers\Dosen\DosenController::class, 'storeAbsensi'])->name('absensi.store');
    Route::post('/toggle-absensi', [App\Http\Controllers\Dosen\DosenController::class, 'toggleAbsensi'])->name('toggle-absensi');
    
    // Laporan
    Route::get('/laporan-absensi', [App\Http\Controllers\Dosen\DosenController::class, 'laporanAbsensi'])->name('laporan-absensi');
    
    // KRS Management untuk Dosen
    Route::get('/krs', [App\Http\Controllers\Dosen\KrsController::class, 'index'])->name('krs.index');
    Route::get('/krs/mahasiswa/{nim}', [App\Http\Controllers\Dosen\KrsController::class, 'showMahasiswa'])->name('krs.mahasiswa');
    Route::get('/profile', [App\Http\Controllers\Dosen\DosenController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Dosen\DosenController::class, 'updateProfile'])->name('profile.update');
});

// Mahasiswa routes
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'dashboard'])->name('dashboard');
    
    // KRS
    Route::get('/krs', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'krs'])->name('krs');
    Route::post('/krs/add', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'addKrs'])->name('krs.add');
    Route::post('/krs/store', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storeKrs'])->name('krs.store');
    Route::delete('/krs/{id}', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'removeKrs'])->name('krs.remove');
  
    
    // Jadwal Kuliah
    Route::get('/jadwal', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'jadwal'])->name('jadwal');
    
    // Add missing routes
    Route::get('/jadwal-tersedia', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'jadwalTersedia'])->name('jadwal.tersedia');
    Route::get('/absensi', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'absensi'])->name('absensi');
    
    // Add this missing route for storing attendance
    Route::post('/absensi/store', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storeAbsensi'])->name('absensi.store');
    
    // Presensi
    Route::get('/presensi', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'storePresensi'])->name('presensi.store');
    
    // Transkrip/Nilai
    Route::get('/transkrip', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'transkrip'])->name('transkrip');
    Route::get('/profile', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'updateProfile'])->name('profile.update');
});

// API Routes (untuk AJAX calls)
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Dashboard API
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/activities', [DashboardController::class, 'getRecentActivities'])->name('dashboard.activities');
    
    // Jadwal API
    Route::get('/jadwal/today', [App\Http\Controllers\Admin\JadwalController::class, 'getTodaySchedule'])->name('jadwal.today');
    Route::get('/jadwal/week', [App\Http\Controllers\Admin\JadwalController::class, 'getWeekSchedule'])->name('jadwal.week');
    
    // KRS API
    Route::get('/krs/available', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'getAvailableKrs'])->name('krs.available');
    
    // Presensi API
    Route::get('/presensi/check/{jadwal_id}', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'checkPresensi'])->name('presensi.check');
});

// Fallback route untuk 404
Route::fallback(function () {
    return view('errors.404');
});