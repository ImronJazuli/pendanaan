<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_dana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kausa_id')->constrained('kausa')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('judul');
            $table->text('ringkasan')->nullable();
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->decimal('total_digunakan', 15, 2)->default(0);
            $table->string('status', 30)->default('draf')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamp('dipublikasikan_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['kausa_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_dana');
    }
};
