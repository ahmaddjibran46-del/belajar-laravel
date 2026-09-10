<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan', 20)->unique(); // contoh: GC-20260902-A1B2
            $table->foreignId('meja_id')->nullable()->constrained('mejas')->nullOnDelete();
            $table->string('nomor_meja_snapshot')->nullable(); // jaga-jaga jika meja dihapus
            $table->enum('tipe', ['dine_in', 'take_away'])->default('dine_in');
            $table->string('nama_pelanggan')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'menunggu_pembayaran',
                'dibayar',
                'diproses',
                'siap',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            $table->unsignedInteger('total_harga')->default(0);
            $table->foreignId('kasir_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
