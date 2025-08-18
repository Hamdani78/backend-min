<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ========== LANDING ==========
Route::get('/', function () {
    return view('landing');
})->name('landing');

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

    // ====== Pendaftaran ======
    Route::prefix('user/pendaftaran')->name('user.pendaftaran.')->group(function () {
        Route::get('/',        [\App\Http\Controllers\User\PendaftaranController::class, 'create'])->name('create');
        Route::post('/',       [\App\Http\Controllers\User\PendaftaranController::class, 'store'])->name('store');
        Route::get('/detail',  [\App\Http\Controllers\User\PendaftaranController::class, 'show'])->name('show');

        // ====== Formulir Terpisah ======
        Route::get('/formulir-terpisah', [\App\Http\Controllers\User\PendaftaranController::class, 'formulirTerpisah'])
            ->name('formulir_terpisah');

        // Data Diri 
        Route::post('/data-diri', [\App\Http\Controllers\User\PendaftaranController::class, 'storeDataDiri'])
            ->name('data_diri.store');
        Route::post('/data-diri/update',  [\App\Http\Controllers\User\PendaftaranController::class, 'updateDataDiri'])
            ->name('data_diri.update');

        // Orang Tua 
        Route::post('/orang-tua/ayah', [\App\Http\Controllers\User\PendaftaranController::class, 'upsertAyah'])
            ->name('orang_tua.ayah.upsert');
        Route::post('/orang-tua/ibu',  [\App\Http\Controllers\User\PendaftaranController::class, 'upsertIbu'])
            ->name('orang_tua.ibu.upsert');

        // Wali
        Route::post('/wali', [\App\Http\Controllers\User\PendaftaranController::class, 'upsertWali'])
            ->name('wali.upsert');
    });

    // ====== Upload Berkas ======
    Route::get('/user/upload-berkas',         [\App\Http\Controllers\User\UploadBerkasController::class, 'create'])->name('user.berkas.create');
    Route::post('/user/upload-berkas',        [\App\Http\Controllers\User\UploadBerkasController::class, 'store'])->name('user.berkas.store');
    Route::get('/user/upload-berkas/detail',  [\App\Http\Controllers\User\UploadBerkasController::class, 'show'])->name('user.berkas.show');
    Route::get('/user/upload-berkas/edit',    [\App\Http\Controllers\User\UploadBerkasController::class, 'edit'])->name('user.berkas.edit');
    Route::post('/user/upload-berkas/update', [\App\Http\Controllers\User\UploadBerkasController::class, 'update'])->name('user.berkas.update');

    // ====== Pengumuman & Daftar Ulang ======
    Route::get('/user/pengumuman', [\App\Http\Controllers\User\PengumumanController::class, 'index'])->name('user.pengumuman');
    Route::get('/user/daftar-ulang',  [\App\Http\Controllers\User\DaftarUlangController::class, 'create'])->name('daftar-ulang.create');
    Route::post('/user/daftar-ulang', [\App\Http\Controllers\User\DaftarUlangController::class, 'store'])->name('daftar-ulang.store');

    // ====== Cetak Bukti ======
    Route::get('/user/cetak-bukti', [\App\Http\Controllers\User\PendaftaranController::class, 'cetakBukti'])->name('user.cetak.bukti');
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
        'users' => \App\Http\Controllers\Admin\UserManagementController::class,
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

    // ========= Verifikasi Pendaftar =========
    Route::prefix('pendaftar')->name('admin.pendaftar.')->group(function () {
        Route::post('/{pendaftar}/verify',      [\App\Http\Controllers\Admin\PendaftarVerificationController::class, 'verify'])->name('verify');
        Route::post('/{pendaftar}/unverify',    [\App\Http\Controllers\Admin\PendaftarVerificationController::class, 'unverify'])->name('unverify');
        Route::post('/{pendaftar}/request-fix', [\App\Http\Controllers\Admin\PendaftarVerificationController::class, 'requestFix'])->name('request_fix');
    });

    Route::post('/berkas-pendaftaran/{berkas_pendaftaran}/verify',
      [\App\Http\Controllers\Admin\BerkasPendaftaranController::class, 'verify'])->name('berkas-pendaftaran.verify');

    Route::post('/berkas-pendaftaran/{berkas_pendaftaran}/wawancara',
      [\App\Http\Controllers\Admin\BerkasPendaftaranController::class, 'setWawancara'])->name('berkas-pendaftaran.wawancara');

    // ========= SPK =========
    Route::get('/spk', [\App\Http\Controllers\Admin\SpkController::class, 'index'])->name('spk.index');
    Route::post('/spk', [\App\Http\Controllers\Admin\SpkController::class, 'store'])->name('spk.store');
    Route::get('/spk/proses', [\App\Http\Controllers\Admin\SpkController::class, 'proses'])->name('spk.proses');
    Route::get('/spk/hasil', fn() => Inertia::render('Admin/Spk/Hasil'))->name('spk.hasil');
    Route::get('/spk/pdf', [\App\Http\Controllers\Admin\SpkController::class, 'exportPdf'])->name('spk.pdf');
    Route::get('/spk/excel', [\App\Http\Controllers\Admin\SpkController::class, 'exportExcel'])->name('spk.excel');

    Route::post('/spk/terapkan', [\App\Http\Controllers\Admin\SpkController::class, 'terapkanHasil'])
        ->name('admin.spk.terapkan');
    Route::post('/spk/simpan-hasil', [\App\Http\Controllers\Admin\SpkController::class, 'simpanKeSession'])
        ->name('spk.simpan');

    // ========= Daftar Ulang =========
    Route::get('/daftar-ulang', [\App\Http\Controllers\Admin\DaftarUlangController::class, 'index'])->name('admin.daftar-ulang.index');
    Route::post('/daftar-ulang/{id}/verifikasi', [\App\Http\Controllers\Admin\DaftarUlangController::class, 'verifikasi'])->name('admin.daftar-ulang.verifikasi');
    Route::post('/daftar-ulang/{id}/selesai', [\App\Http\Controllers\Admin\DaftarUlangController::class, 'selesaikan'])->name('admin.daftar-ulang.selesai');
});

// ========== LANDING PAGES ==========
Route::get('/landing/fasilitas', [\App\Http\Controllers\Landing\FasilitasLandingController::class, 'index']);
Route::get('/landing/kegiatan', [\App\Http\Controllers\Landing\KegiatanLandingController::class, 'index']);
Route::get('/landing/pegawai', [\App\Http\Controllers\Landing\PegawaiLandingController::class, 'index']);
Route::get('/landing/siswa', [\App\Http\Controllers\Landing\SiswaLandingController::class, 'index']);


// ========== KEPALA SEKOLAH ==========
Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Kepsek\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pegawai', [\App\Http\Controllers\Kepsek\PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pendaftar', [\App\Http\Controllers\Kepsek\PendaftarController::class, 'index'])->name('pendaftar.index');
});
