<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donasi_id')->unique()->constrained('donasi')->restrictOnDelete();
            $table->string('penyedia', 50)->default('simulasi');
            $table->string('referensi_penyedia')->nullable()->index();
            $table->string('token_pembayaran')->nullable();
            $table->string('status', 20)->default('menunggu')->index();
            $table->json('respons_penyedia')->nullable();
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamp('kedaluwarsa_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran');
    }
};
