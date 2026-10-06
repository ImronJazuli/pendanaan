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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom-kolom baru yang belum ada
            $table->string('role', 30)->default('donatur')->index()->after('password');
            $table->string('nik', 16)->nullable()->unique()->after('role');
            $table->string('phone_number', 20)->nullable()->after('nik');
            $table->string('sso_id')->nullable()->unique()->after('phone_number');
            $table->string('google_id')->nullable()->unique()->after('sso_id');
            $table->string('avatar')->nullable()->after('google_id');
            $table->string('npwp', 20)->nullable()->unique()->after('nik');
            $table->text('address')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'nik',
                'phone_number',
                'sso_id',
                'google_id',
                'avatar',
                'npwp',
                'address',
            ]);
        });
    }
};
