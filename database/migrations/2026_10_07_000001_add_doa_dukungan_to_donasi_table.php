<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            if (! Schema::hasColumn('donasi', 'doa_dukungan')) {
                $table->text('doa_dukungan')->nullable()->after('anonim');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            if (Schema::hasColumn('donasi', 'doa_dukungan')) {
                $table->dropColumn('doa_dukungan');
            }
        });
    }
};
