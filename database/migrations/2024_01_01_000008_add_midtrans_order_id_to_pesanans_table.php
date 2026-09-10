<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Menyimpan order_id Midtrans (kode_pesanan + timestamp) secara PERMANEN
            // di database, supaya tidak hilang kalau session browser bermasalah.
            $table->string('midtrans_order_id')->nullable()->after('kode_pesanan');
        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('midtrans_order_id');
        });
    }
};
