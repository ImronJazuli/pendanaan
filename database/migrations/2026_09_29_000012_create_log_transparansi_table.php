<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_transparansi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kausa_id')->constrained('kausa')->restrictOnDelete();
            $table->foreignId('admin_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('laporan_dana_id')->nullable()->constrained('laporan_dana')->nullOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->string('path_bukti')->nullable();
            $table->boolean('dipublikasikan')->default(false)->index();
            $table->timestamp('dipublikasikan_pada')->nullable();
            $table->timestamps();
            $table->index(['kausa_id', 'dipublikasikan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_transparansi');
    }
};
