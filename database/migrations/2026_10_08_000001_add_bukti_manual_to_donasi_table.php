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
        Schema::table('donasi', function (Blueprint $table) {
            if (! Schema::hasColumn('donasi', 'path_bukti_manual')) {
                $table->string('path_bukti_manual')->nullable()->after('status');
            }
            if (! Schema::hasColumn('donasi', 'catatan_verifikasi_manual')) {
                $table->text('catatan_verifikasi_manual')->nullable()->after('path_bukti_manual');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            if (Schema::hasColumn('donasi', 'catatan_verifikasi_manual')) {
                $table->dropColumn('catatan_verifikasi_manual');
            }
            if (Schema::hasColumn('donasi', 'path_bukti_manual')) {
                $table->dropColumn('path_bukti_manual');
            }
        });
    }
};
