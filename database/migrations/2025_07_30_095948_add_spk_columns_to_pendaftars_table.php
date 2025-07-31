<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->decimal('nilai_spk', 5, 2)->nullable();
            $table->enum('status_lulus', ['lulus', 'tidak_lulus'])->nullable();
        });
    }

    public function down()
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['nilai_spk', 'status_lulus']);
        });
    }
};
