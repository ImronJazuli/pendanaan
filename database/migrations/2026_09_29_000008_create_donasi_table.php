<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kausa_id')->constrained('kausa')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pesanan_pembayaran')->unique();
            $table->string('nama_donatur')->nullable();
            $table->boolean('anonim')->default(false);
            $table->string('email_donatur')->nullable();
            $table->string('telepon_donatur', 30)->nullable();
            $table->decimal('nominal', 15, 2);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('status', 20)->default('menunggu')->index();
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamps();
            $table->index(['kausa_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
