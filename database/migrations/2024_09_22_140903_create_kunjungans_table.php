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
        Schema::create('table_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mahasiswa')->constrained('table_mahasiswa')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_kunjungan');
    }
};
