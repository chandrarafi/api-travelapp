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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->onDelete('cascade');
            $table->string('metode_pembayaran');
            $table->bigInteger('jumlah_bayar');
            $table->string('bukti_pembayaran')->nullable(); // URL / file path foto bukti transfer/pembayaran
            $table->string('status')->default('menunggu'); // menunggu, berhasil, gagal
            $table->timestamp('tanggal_pembayaran')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
