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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mobil_id')->constrained('mobil')->onDelete('cascade');
            $table->string('kode_pemesanan')->unique();
            $table->date('tanggal_keberangkatan');
            $table->integer('jumlah_kursi');
            $table->bigInteger('total_bayar');
            $table->string('status_pembayaran')->default('pending'); // pending, lunas, batal
            $table->string('metode_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
