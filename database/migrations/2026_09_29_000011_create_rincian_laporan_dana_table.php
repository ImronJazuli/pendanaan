<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rincian_laporan_dana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_dana_id')->constrained('laporan_dana')->cascadeOnDelete();
            $table->string('uraian');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_pengeluaran')->nullable();
            $table->string('penerima_manfaat')->nullable();
            $table->string('path_bukti')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index('tanggal_pengeluaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rincian_laporan_dana');
    }
};
