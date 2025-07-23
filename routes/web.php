<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\FasilitasImagesController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\KegiatanImagesController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin login routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});

// Admin protected routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::resource('/siswa', SiswaController::class);
    Route::resource('/pegawai', PegawaiController::class);
    Route::resource('/fasilitas', FasilitasController::class);
    Route::resource('/kegiatan', KegiatanController::class);

    Route::get('/fasilitas/{fasilitasId}/images', [FasilitasImagesController::class, 'index'])->name('fasilitasimage.index');
    Route::post('/fasilitas/{fasilitasId}/images', [FasilitasImagesController::class, 'store'])->name('fasilitasimage.store');
    Route::delete('/fasilitas/images/{fasilitasImageId}', [FasilitasImagesController::class, 'destroy'])->name('fasilitasimage.destroy');

    Route::get('/kegiatan/{kegiatanId}/images', [KegiatanImagesController::class, 'index'])->name('kegiatanimage.index');
    Route::post('/kegiatan/{kegiatanId}/images', [KegiatanImagesController::class, 'store'])->name('kegiatanimage.store');
    Route::delete('/kegiatan/images/{kegiatanImageId}', [KegiatanImagesController::class, 'destroy'])->name('kegiatanimage.destroy');
});

require __DIR__ . '/auth.php';