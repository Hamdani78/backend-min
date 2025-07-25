<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('no_kk')->nullable();
            $table->string('nik')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama')->nullable();
            $table->float('berat_badan')->nullable();
            $table->float('tinggi_badan')->nullable();
            $table->string('cita_cita')->nullable();
            $table->string('hobi')->nullable();
            $table->string('bahasa')->nullable();
            $table->text('keadaan_jasmani')->nullable();
            $table->text('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('tinggal_dengan', ['Orang Tua', 'Wali'])->nullable();
            $table->enum('pembiaya', ['Orang Tua', 'Wali'])->nullable();
            $table->string('jarak_ke_madrasah')->nullable();
            $table->string('kebutuhan_khusus')->nullable();
            $table->string('kebutuhan_disabilitas')->nullable();
            $table->json('imunisasi')->nullable(); 
            $table->enum('pra_sekolah', ['TK/RA', 'PAUD'])->nullable();
            $table->string('nama_pra_sekolah')->nullable();
            $table->string('kip_nama')->nullable();
            $table->string('kip_nomor')->nullable();
            $table->string('foto')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
