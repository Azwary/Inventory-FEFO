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
        Schema::create('tr_barang_keluar', function (Blueprint $table) {
            $table->string('id_tr_keluar', 5)->primary();
            $table->string('id_keluar', 5);
            $table->string('keterangan')->nullable();


            $table->foreign('id_keluar')->references('id_keluar')->on('barang_keluar')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_barang_keluar');
    }
};
