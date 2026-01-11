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
        Schema::create('stok_barang', function (Blueprint $table) {
            $table->string('id_stok', 5)->primary();
            $table->string('id_barang', 5);
            $table->string('id_lokasi', 5);
            $table->string('nomor_batch', 10);
            $table->date('tanggal_masuk');
            $table->date('tanggal_kadaluarsa');
            $table->integer('jumlah_stok');
            $table->integer('jumlah_sisa')->default('0');

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barang');
    }
};
