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
        Schema::create('kursi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobil_id')->constrained('mobil')->onDelete('cascade');
            $table->string('nomor_kursi');
            $table->string('status')->default('tersedia'); // tersedia, terpesan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kursi');
    }
};
