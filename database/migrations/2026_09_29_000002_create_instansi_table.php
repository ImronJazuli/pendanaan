<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->restrictOnDelete();
            $table->string('jenis', 40);
            $table->string('nama');
            $table->string('nomor_registrasi', 100)->nullable();
            $table->string('status_verifikasi', 30)->default('menunggu_verifikasi')->index();
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon', 30)->nullable();
            $table->timestamp('terverifikasi_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instansi');
    }
};
