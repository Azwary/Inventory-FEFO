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
        Schema::create('obat', function (Blueprint $table) {
            $table->string('id_obat', 5)->primary();
            $table->string('nama_obat');
            $table->string('id_jenis', 5);
            $table->string('id_satuan', 5);
            $table->string('id_lokasi', 5);
            $table->timestamps();

            $table->foreign('id_detail_obat')->references('id_detail_obat')->on('detail_obat')->onDelete('cascade');
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis')->onDelete('cascade');
            $table->foreign('id_satuan')->references('id_satuan')->on('satuan')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
