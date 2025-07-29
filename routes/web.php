<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ========== LANDING ==========
Route::get('/', function () {
    return view('landing');
});

Route::get('/min/{any}', function () {
    return view('landing'); 
})->where('any', '.*');


// ========== USER AUTH ==========
use App\Http\Controllers\User\UserController;

Route::middleware('guest')->group(function () {
    Route::get('/user/login', [UserController::class, 'showLoginForm'])->name('user.login');
    Route::post('/user/login', [UserController::class, 'login']);
    Route::get('/user/register', [UserController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/user/register', [UserController::class, 'register']);
});

Route::middleware(['auth', 'role:pendaftar'])->group(function () {
    Route::get('/user/pendaftaran', [\App\Http\Controllers\User\PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/user/pendaftaran', [\App\Http\Controllers\User\PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/user/pendaftaran/detail', [\App\Http\Controllers\User\PendaftaranController::class, 'show'])->name('user.pendaftaran.show');
    Route::get('/user/pendaftaran/edit', [\App\Http\Controllers\User\PendaftaranController::class, 'edit'])->name('user.pendaftaran.edit');
    Route::put('/user/pendaftaran/update', [\App\Http\Controllers\User\PendaftaranController::class, 'update'])->name('user.pendaftaran.update');


    Route::get('/user/upload-berkas', [\App\Http\Controllers\User\UploadBerkasController::class, 'create'])->name('user.berkas.create');
    Route::post('/user/upload-berkas', [\App\Http\Controllers\User\UploadBerkasController::class, 'store'])->name('user.berkas.store');

    Route::get('/user/daftar-ulang', [\App\Http\Controllers\User\DaftarUlangController::class, 'create'])->name('daftar-ulang.create');
    Route::post('/user/daftar-ulang', [\App\Http\Controllers\User\DaftarUlangController::class, 'store'])->name('daftar-ulang.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/user/logout', [UserController::class, 'logout'])->name('user.logout');

    Route::get('/user/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
});


// ========== ADMIN AUTH ==========
use App\Http\Controllers\Admin\AdminController;

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});


// ========== ADMIN DASHBOARD & RESOURCES ==========
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::resources([
        'siswa' => \App\Http\Controllers\Admin\SiswaController::class,
        'pegawai' => \App\Http\Controllers\Admin\PegawaiController::class,
        'fasilitas' => \App\Http\Controllers\Admin\FasilitasController::class,
        'kegiatan' => \App\Http\Controllers\Admin\KegiatanController::class,
        'pendaftar' => \App\Http\Controllers\Admin\PendaftarController::class,
        'berkas-pendaftaran' => \App\Http\Controllers\Admin\BerkasPendaftaranController::class,
    ]);

    // ========= Fasilitas Images =========
    Route::get('/fasilitas/{fasilitasId}/images', [\App\Http\Controllers\Admin\FasilitasImagesController::class, 'index'])->name('fasilitasimage.index');
    Route::post('/fasilitas/{fasilitasId}/images', [\App\Http\Controllers\Admin\FasilitasImagesController::class, 'store'])->name('fasilitasimage.store');
    Route::delete('/fasilitas/images/{fasilitasImageId}', [\App\Http\Controllers\Admin\FasilitasImagesController::class, 'destroy'])->name('fasilitasimage.destroy');

    // ========= Kegiatan Images =========
    Route::get('/kegiatan/{kegiatanId}/images', [\App\Http\Controllers\Admin\KegiatanImagesController::class, 'index'])->name('kegiatanimage.index');
    Route::post('/kegiatan/{kegiatanId}/images', [\App\Http\Controllers\Admin\KegiatanImagesController::class, 'store'])->name('kegiatanimage.store');
    Route::delete('/kegiatan/images/{kegiatanImageId}', [\App\Http\Controllers\Admin\KegiatanImagesController::class, 'destroy'])->name('kegiatanimage.destroy');

    // ========= Status Pendaftar =========
    Route::post('/pendaftar/{pendaftar}/status', [\App\Http\Controllers\Admin\PendaftarStatusController::class, 'update'])->name('admin.pendaftar.status.update');

    // ========= SPK =========
    Route::get('/spk', [\App\Http\Controllers\Admin\SpkController::class, 'index'])->name('spk.index');
    Route::post('/spk', [\App\Http\Controllers\Admin\SpkController::class, 'store'])->name('spk.store');
    Route::get('/spk/proses', [\App\Http\Controllers\Admin\SpkController::class, 'proses'])->name('spk.proses');
    Route::get('/spk/hasil', fn() => Inertia::render('Admin/Spk/Hasil'))->name('spk.hasil');
    Route::get('/spk/pdf', [\App\Http\Controllers\Admin\SpkController::class, 'exportPdf'])->name('spk.pdf');
    Route::get('/spk/excel', [\App\Http\Controllers\Admin\SpkController::class, 'exportExcel'])->name('spk.excel');

    // ========= Daftar Ulang =========
    Route::get('/daftar-ulang', [\App\Http\Controllers\Admin\DaftarUlangController::class, 'index'])->name('admin.daftar-ulang.index');
    Route::post('/daftar-ulang/{id}/verifikasi', [\App\Http\Controllers\Admin\DaftarUlangController::class, 'verifikasi'])->name('admin.daftar-ulang.verifikasi');
});

// ========== LANDING PAGES ==========
Route::get('/landing/fasilitas', [\App\Http\Controllers\Landing\FasilitasLandingController::class, 'index']);
Route::get('/landing/kegiatan', [\App\Http\Controllers\Landing\KegiatanLandingController::class, 'index']);
Route::get('/landing/pegawai', [\App\Http\Controllers\Landing\PegawaiLandingController::class, 'index']);

