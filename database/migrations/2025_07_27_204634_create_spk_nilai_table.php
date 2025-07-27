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
        Schema::create('spk_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained()->onDelete('cascade');
            $table->float('umur');        // e.g. 0.5 atau 0.0
            $table->float('zonasi');      // e.g. 0.3, 0.2, 0.1
            $table->float('berkas');      // e.g. 0.15, 0.10, 0.05
            $table->float('wawancara');   // e.g. 0.05, 0.03, 0.01
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_nilai');
    }
};
