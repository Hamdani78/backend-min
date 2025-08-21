<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Controller Imports
|--------------------------------------------------------------------------
*/

// Landing
use App\Http\Controllers\Landing\FasilitasLandingController;
use App\Http\Controllers\Landing\KegiatanLandingController;
use App\Http\Controllers\Landing\PegawaiLandingController;
use App\Http\Controllers\Landing\SiswaLandingController;
use App\Http\Controllers\Landing\KamadController;
use App\Http\Controllers\Landing\PpdbInfoController;

// User (Auth & Pages)
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PendaftaranController as UserPendaftaranController;
use App\Http\Controllers\User\UploadBerkasController as UserUploadBerkasController;
use App\Http\Controllers\User\PengumumanController as UserPengumumanController;
use App\Http\Controllers\User\DaftarUlangController as UserDaftarUlangController;

// Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;
use App\Http\Controllers\Admin\BerkasPendaftaranController as AdminBerkasPendaftaranController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\FasilitasImagesController;
use App\Http\Controllers\Admin\KegiatanImagesController;
use App\Http\Controllers\Admin\PendaftarStatusController;
use App\Http\Controllers\Admin\PendaftarVerificationController;
use App\Http\Controllers\Admin\SpkController;
use App\Http\Controllers\Admin\DaftarUlangController as AdminDaftarUlangController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\SettingPpdbController;

// Kepsek
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboardController;
use App\Http\Controllers\Kepsek\PegawaiController as KepsekPegawaiController;
use App\Http\Controllers\Kepsek\PendaftarController as KepsekPendaftarController;


/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('landing'))->name('landing');

// (opsional) SPA/fe route untuk subpath /min/*
Route::get('/min/{any}', fn () => view('landing'))->where('any', '.*');


/*
|--------------------------------------------------------------------------
| USER AUTH (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/user/login',    [UserController::class, 'showLoginForm'])->name('user.login');
    Route::post('/user/login',   [UserController::class, 'login']);
    Route::get('/user/register', [UserController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/user/register',[UserController::class, 'register']);
});


/*
|--------------------------------------------------------------------------
| USER AUTHED (COMMON)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/user/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});


/*
|--------------------------------------------------------------------------
| PPDB CLOSED PAGE
|--------------------------------------------------------------------------
*/
Route::get('/ppdb/closed', [PpdbInfoController::class, 'closed'])->name('ppdb.closed');


/*
|--------------------------------------------------------------------------
| USER (PENDAFTAR)
|--------------------------------------------------------------------------
| Catatan:
| - READ routes (lihat detail, pengumuman, dsb) tetap bisa diakses saat PPDB tutup.
| - WRITE routes (create/store/update) dilindungi middleware 'ppdb.open'.
*/
Route::middleware(['auth', 'role:pendaftar'])->group(function () {

    // ---------- READ: Pendaftaran ----------
    Route::prefix('user/pendaftaran')->name('user.pendaftaran.')->group(function () {
        // Tampilkan detail data pendaftaran
        Route::get('/detail', [UserPendaftaranController::class, 'show'])->name('show');
        // JANGAN tempatkan GET '/' (create) di sini jika ingin tertutup saat PPDB tutup
    });

    // ---------- READ: Upload Berkas ----------
    Route::get('/user/upload-berkas',        [UserUploadBerkasController::class, 'create'])->name('user.berkas.create');
    Route::get('/user/upload-berkas/detail', [UserUploadBerkasController::class, 'show'])->name('user.berkas.show');

    // ---------- Pengumuman & Daftar Ulang ----------
    Route::get('/user/pengumuman', [UserPengumumanController::class, 'index'])->name('user.pengumuman');

    // Daftar ulang umumnya tetap dibuka walau PPDB awal sudah tutup
    Route::get('/user/daftar-ulang',  [UserDaftarUlangController::class, 'create'])->name('daftar-ulang.create');
    Route::post('/user/daftar-ulang', [UserDaftarUlangController::class, 'store'])->name('daftar-ulang.store');

    // Cetak bukti
    Route::get('/user/cetak-bukti', [UserPendaftaranController::class, 'cetakBukti'])->name('user.cetak.bukti');
});

// ---------- WRITE: Pendaftaran (dilindungi ppdb.open) ----------
Route::middleware(['auth','role:pendaftar','ppdb.open'])
    ->prefix('user/pendaftaran')->name('user.pendaftaran.')
    ->group(function () {
        // Form pendaftaran (create) & submit
        Route::get('/',  [UserPendaftaranController::class, 'create'])->name('create');
        Route::post('/', [UserPendaftaranController::class, 'store'])->name('store');

        // Formulir terpisah (jika ini bagian dari pengisian data, lindungi juga)
        Route::get('/formulir-terpisah', [UserPendaftaranController::class, 'formulirTerpisah'])->name('formulir_terpisah');

        // Data Diri
        Route::post('/data-diri',        [UserPendaftaranController::class, 'storeDataDiri'])->name('data_diri.store');
        Route::post('/data-diri/update', [UserPendaftaranController::class, 'updateDataDiri'])->name('data_diri.update');

        // Orang Tua
        Route::post('/orang-tua/ayah',   [UserPendaftaranController::class, 'upsertAyah'])->name('orang_tua.ayah.upsert');
        Route::post('/orang-tua/ibu',    [UserPendaftaranController::class, 'upsertIbu'])->name('orang_tua.ibu.upsert');

        // Wali
        Route::post('/wali',             [UserPendaftaranController::class, 'upsertWali'])->name('wali.upsert');
    });

// ---------- WRITE: Upload Berkas (opsional dilindungi ppdb.open) ----------
Route::middleware(['auth','role:pendaftar','ppdb.open'])->group(function () {
    Route::post('/user/upload-berkas',        [UserUploadBerkasController::class, 'store'])->name('user.berkas.store');
    Route::get('/user/upload-berkas/edit',    [UserUploadBerkasController::class, 'edit'])->name('user.berkas.edit');
    Route::post('/user/upload-berkas/update', [UserUploadBerkasController::class, 'update'])->name('user.berkas.update');
});


/*
|--------------------------------------------------------------------------
| ADMIN AUTH (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login',  [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    // Dashboard & Logout
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout',   [AdminController::class, 'logout'])->name('admin.logout');

    // Resources
    Route::resources([
        'siswa'               => SiswaController::class,
        'pegawai'             => PegawaiController::class,
        'fasilitas'           => FasilitasController::class,
        'kegiatan'            => KegiatanController::class,
        'pendaftar'           => AdminPendaftarController::class,
        'berkas-pendaftaran'  => AdminBerkasPendaftaranController::class,
        'users'               => UserManagementController::class,
    ]);

    // Fasilitas Images
    Route::get('/fasilitas/{fasilitasId}/images', [FasilitasImagesController::class, 'index'])->name('fasilitasimage.index');
    Route::post('/fasilitas/{fasilitasId}/images', [FasilitasImagesController::class, 'store'])->name('fasilitasimage.store');
    Route::delete('/fasilitas/images/{fasilitasImageId}', [FasilitasImagesController::class, 'destroy'])->name('fasilitasimage.destroy');

    // Kegiatan Images
    Route::get('/kegiatan/{kegiatanId}/images',  [KegiatanImagesController::class, 'index'])->name('kegiatanimage.index');
    Route::post('/kegiatan/{kegiatanId}/images', [KegiatanImagesController::class, 'store'])->name('kegiatanimage.store');
    Route::delete('/kegiatan/images/{kegiatanImageId}', [KegiatanImagesController::class, 'destroy'])->name('kegiatanimage.destroy');

    // Status Pendaftar
    Route::post('/pendaftar/{pendaftar}/status', [PendaftarStatusController::class, 'update'])->name('admin.pendaftar.status.update');

    // Verifikasi Pendaftar
    Route::prefix('pendaftar')->name('admin.pendaftar.')->group(function () {
        Route::post('/{pendaftar}/verify',      [PendaftarVerificationController::class, 'verify'])->name('verify');
        Route::post('/{pendaftar}/unverify',    [PendaftarVerificationController::class, 'unverify'])->name('unverify');
        Route::post('/{pendaftar}/request-fix', [PendaftarVerificationController::class, 'requestFix'])->name('request_fix');
    });

    // Berkas Pendaftaran (verify & set wawancara)
    Route::post('/berkas-pendaftaran/{berkas_pendaftaran}/verify',    [AdminBerkasPendaftaranController::class, 'verify'])->name('berkas-pendaftaran.verify');
    Route::post('/berkas-pendaftaran/{berkas_pendaftaran}/wawancara', [AdminBerkasPendaftaranController::class, 'setWawancara'])->name('berkas-pendaftaran.wawancara');

    // SPK
    Route::get('/spk',           [SpkController::class, 'index'])->name('spk.index');
    Route::post('/spk',          [SpkController::class, 'store'])->name('spk.store');
    Route::get('/spk/proses',    [SpkController::class, 'proses'])->name('spk.proses');
    Route::get('/spk/hasil', fn () => Inertia::render('Admin/Spk/Hasil'))->name('spk.hasil');
    Route::get('/spk/pdf',       [SpkController::class, 'exportPdf'])->name('spk.pdf');
    Route::get('/spk/excel',     [SpkController::class, 'exportExcel'])->name('spk.excel');
    Route::post('/spk/terapkan', [SpkController::class, 'terapkanHasil'])->name('admin.spk.terapkan');
    Route::post('/spk/simpan-hasil', [SpkController::class, 'simpanKeSession'])->name('spk.simpan');

    // Daftar Ulang (Admin)
    Route::get('/daftar-ulang',                    [AdminDaftarUlangController::class, 'index'])->name('admin.daftar-ulang.index');
    Route::post('/daftar-ulang/{id}/verifikasi',   [AdminDaftarUlangController::class, 'verifikasi'])->name('admin.daftar-ulang.verifikasi');
    Route::post('/daftar-ulang/{id}/selesai',      [AdminDaftarUlangController::class, 'selesaikan'])->name('admin.daftar-ulang.selesai');

    // Content
    Route::resource('content', ContentController::class)->only(['index','create','store','edit','update','destroy']);

    // Settings PPDB (Buka/Tutup PPDB)
    Route::prefix('settings')->name('admin.settings.')->group(function () {
        Route::get('/ppdb',  [SettingPpdbController::class, 'edit'])->name('ppdb.edit');
        Route::post('/ppdb', [SettingPpdbController::class, 'update'])->name('ppdb.update');
    });
});


/*
|--------------------------------------------------------------------------
| LANDING PAGES (Publik)
|--------------------------------------------------------------------------
*/
Route::get('/landing/fasilitas', [FasilitasLandingController::class, 'index']);
Route::get('/landing/kegiatan',  [KegiatanLandingController::class,  'index']);
Route::get('/landing/pegawai',   [PegawaiLandingController::class,   'index']);
Route::get('/landing/siswa',     [SiswaLandingController::class,     'index']);
Route::get('/landing/kamad',     [KamadController::class,            'index']);


/*
|--------------------------------------------------------------------------
| KEPALA SEKOLAH
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', [KepsekDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pegawai',   [KepsekPegawaiController::class,   'index'])->name('pegawai.index');
    Route::get('/pendaftar', [KepsekPendaftarController::class, 'index'])->name('pendaftar.index');
});
