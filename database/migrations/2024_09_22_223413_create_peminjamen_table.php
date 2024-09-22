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
        Schema::create('table_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_staff')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_mahasiswa')->constrained('table_mahasiswa')->onDelete('cascade');
            $table->foreignId('id_buku')->constrained('table_buku')->onDelete('cascade');
            $table->integer('durasi_peminjaman');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian');
            $table->enum('status', ['peminjaman', 'selesai'])->default('peminjaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_peminjaman');
    }
};
