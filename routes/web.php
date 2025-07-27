<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\FasilitasImagesController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\KegiatanImagesController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\BerkasPendaftaranController;

// User Controllers
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\PendaftaranController;
use App\Http\Controllers\User\UploadBerkasController;
use App\Http\Controllers\User\PengumumanController;
use App\Http\Controllers\User\DaftarUlangController;

// Landing Page
Route::get('/', function () {
    return view('landing'); 
});

// User Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/user/login', [UserController::class, 'showLoginForm'])->name('user.login');
    Route::post('/user/login', [UserController::class, 'login']);
    Route::get('/user/register', [UserController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/user/register', [UserController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/user/logout', [UserController::class, 'logout'])->name('user.logout');

    Route::get('/user/dashboard', function () {
        return Inertia::render(
            auth()->user()->role === 'kepsek' ? 'User/KepsekDashboard' : 'User/PendaftarDashboard'
        );
    })->name('user.dashboard');
});

// Admin Auth Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::resource('/siswa', SiswaController::class);
    Route::resource('/pegawai', PegawaiController::class);
    Route::resource('/fasilitas', FasilitasController::class);
    Route::resource('/kegiatan', KegiatanController::class);
    Route::resource('/pendaftar', PendaftarController::class);
    Route::resource('/berkas-pendaftaran', BerkasPendaftaranController::class);

    Route::get('/fasilitas/{fasilitasId}/images', [FasilitasImagesController::class, 'index'])->name('fasilitasimage.index');
    Route::post('/fasilitas/{fasilitasId}/images', [FasilitasImagesController::class, 'store'])->name('fasilitasimage.store');
    Route::delete('/fasilitas/images/{fasilitasImageId}', [FasilitasImagesController::class, 'destroy'])->name('fasilitasimage.destroy');

    Route::get('/kegiatan/{kegiatanId}/images', [KegiatanImagesController::class, 'index'])->name('kegiatanimage.index');
    Route::post('/kegiatan/{kegiatanId}/images', [KegiatanImagesController::class, 'store'])->name('kegiatanimage.store');
    Route::delete('/kegiatan/images/{kegiatanImageId}', [KegiatanImagesController::class, 'destroy'])->name('kegiatanimage.destroy');
});


// require __DIR__ . '/auth.php';
