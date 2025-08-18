<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Status + indeks
            $table->boolean('is_verified')->default(false)->after('imunisasi');
            $table->index('is_verified');

            // Metadata verifikasi
            $table->timestamp('verified_at')->nullable()->after('is_verified');
            // Jika verifikasi dilakukan oleh admin di tabel 'admins', ganti 'users' jadi 'admins'
            $table->foreignId('verified_by')->nullable()->after('verified_at')
                  ->constrained('admins')->nullOnDelete();

            $table->text('verification_note')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropIndex(['is_verified']);
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['is_verified', 'verified_at', 'verification_note']);
        });
    }
};
