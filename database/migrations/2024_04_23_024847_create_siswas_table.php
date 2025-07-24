<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('kelas');
            $table->integer('laki_laki');     
            $table->integer('perempuan');     
            $table->integer('jml_siswa');     
            $table->unsignedBigInteger('pegawai_id');
            $table->timestamps();

            $table->foreign('pegawai_id')
                ->references('id')
                ->on('pegawais')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
