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
        Schema::create('jaminan_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                ->unique()
                ->constrained('peminjaman')
                ->cascadeOnDelete();

            $table->enum('jenis', ['jaminan', 'pembayaran']);
            $table->string('keterangan', 255)->nullable();
            $table->decimal('nominal', 12, 2)->nullable();
            $table->string('bukti', 255)->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])
                ->default('menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaminan_peminjaman');
    }
};