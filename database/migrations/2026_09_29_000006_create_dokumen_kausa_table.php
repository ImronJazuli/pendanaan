<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_kausa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kausa_id')->constrained('kausa')->restrictOnDelete();
            $table->string('jenis_dokumen', 60);
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('ukuran_file')->nullable();
            $table->string('status_verifikasi', 30)->default('menunggu_verifikasi')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_kausa');
    }
};
