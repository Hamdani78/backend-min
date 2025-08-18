<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Tambah kolom kalau belum ada
            if (!Schema::hasColumn('pendaftars', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('imunisasi');
            }

            if (!Schema::hasColumn('pendaftars', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_verified');
            }

            if (!Schema::hasColumn('pendaftars', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('verified_at')
                      ->constrained('admins')->nullOnDelete();
            }

            if (!Schema::hasColumn('pendaftars', 'verification_note')) {
                $table->text('verification_note')->nullable()->after('verified_by');
            }
        });

        // Tambah index untuk is_verified (jika belum ada)
        // Nama default index: pendaftars_is_verified_index
        try {
            DB::statement('CREATE INDEX pendaftars_is_verified_index ON pendaftars (is_verified)');
        } catch (\Throwable $e) {
            // diabaikan jika index sudah ada
        }
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // drop FK dulu
            if (Schema::hasColumn('pendaftars', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('pendaftars', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('pendaftars', 'verification_note')) {
                $table->dropColumn('verification_note');
            }
            if (Schema::hasColumn('pendaftars', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });

        try {
            DB::statement('DROP INDEX pendaftars_is_verified_index');
        } catch (\Throwable $e) {}
    }
};
