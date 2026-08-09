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
        Schema::create('mobil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rute')->onDelete('cascade');
            $table->string('nama_mobil');
            $table->string('nomor_plat');
            $table->string('jam_keberangkatan'); // e.g. "08:00 WIB"
            $table->bigInteger('harga');
            $table->integer('total_kursi')->default(10);
            $table->string('foto')->nullable(); // URL atau nama file foto mobil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobil');
    }
};
