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
        Schema::create('detail_barang_keluar', function (Blueprint $table) {
            $table->string('id_detail_keluar', 5)->primary();
            $table->string('id_barang_keluar', 5);
            $table->string('id_obat_merek', 5);
            $table->integer('jumlah_keluar', 5);
            $table->timestamps();

            $table->foreign('id_barang_keluar')->references('id_barang_keluar')->on('barang_keluar')->onDelete('cascade');
            $table->foreign('id_obat_merek')->references('id_obat_merek')->on('obat_merek')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang_keluar');
    }
};
