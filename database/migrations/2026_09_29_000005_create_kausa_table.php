<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kausa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('instansi')->restrictOnDelete();
            $table->foreignId('kategori_kausa_id')->nullable()->constrained('kategori_kausa')->nullOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('deskripsi');
            $table->string('lokasi')->nullable();
            $table->decimal('target_dana', 15, 2);
            $table->decimal('dana_terkumpul', 15, 2)->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('status', 30)->default('draf')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('dipublikasikan_pada')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['instansi_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kausa');
    }
};
