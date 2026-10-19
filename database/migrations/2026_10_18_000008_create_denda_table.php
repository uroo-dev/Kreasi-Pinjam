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
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pengembalian_id')->nullable()->constrained('pengembalian')->nullOnDelete();
            $table->enum('jenis_denda', ['terlambat', 'rusak', 'hilang']);
            $table->decimal('nominal', 12, 2);
            $table->decimal('dibayar', 12, 2)->default(0);
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
