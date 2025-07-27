<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('berkas_pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->unique()->constrained()->onDelete('cascade');
            $table->string('ijazah_tk');
            $table->string('akte_kelahiran');
            $table->string('kartu_keluarga');
            $table->string('kip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_pendaftarans');
    }
};
