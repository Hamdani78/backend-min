<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->timestamp('wawancara_at')->nullable()->after('status_pendaftaran');
            $table->string('wawancara_tempat', 150)->nullable()->after('wawancara_at');
            $table->text('wawancara_catatan')->nullable()->after('wawancara_tempat');
        });
    }
    public function down(): void {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['wawancara_at','wawancara_tempat','wawancara_catatan']);
        });
    }
};

