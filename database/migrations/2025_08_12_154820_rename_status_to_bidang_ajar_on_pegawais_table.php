<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->renameColumn('status', 'bidang_ajar');
        });
    }
    public function down(): void {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->renameColumn('bidang_ajar', 'status');
        });
    }
};
