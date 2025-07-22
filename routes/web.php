<?php

use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\SiswaController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('/admin/login');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
    Route::match(['get', 'post'],'login', 'AdminController@login');

    Route::group(['middleware'=>'disablebackbtn'],function(){
        Route::group(["middleware"=>['admin']], function(){
            Route::get('dashboard','AdminController@dashboard');
            Route::get('logout','AdminController@logout');

            Route::resource('siswa', SiswaController::class);
            Route::resource('pegawai', PegawaiController::class);
            Route::resource('fasilitas', FasilitasController::class);
            Route::resource('kegiatan', KegiatanController::class);

            //fasilitas image
            Route::get('fasilitas/fasilitas-images/{fasilitasId}/upload', 'FasilitasImagesController@index')->name('fasilitasimage.index');
            Route::post('fasilitas/fasilitas-images/{fasilitasId}/upload', 'FasilitasImagesController@store')->name('fasilitasimage.store');
            Route::delete('fasilitas/fasilitas-images/{fasilitasImageId}/delete', 'FasilitasImagesController@destroy')->name('fasilitasimage.destroy');
            
            //kegiatan image
            Route::get('kegiatan/kegiatan-images/{kegiatanId}/upload', 'KegiatanImagesController@index')->name('kegiatanimage.index');
            Route::post('kegiatan/kegiatan-images/{kegiatanId}/upload', 'KegiatanImagesController@store')->name('kegiatanimage.store');
            Route::delete('kegiatan/kegiatan-images/{kegiatanImageId}/delete', 'KegiatanImagesController@destroy')->name('kegiatanimage.destroy');
        });
    });
});