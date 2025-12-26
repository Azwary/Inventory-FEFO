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
            $table->id('id_tr_keluar');
            $table->foreignId('id_keluar')->constrained('barang_keluar', 'id_keluar')->onDelete('cascade');
            $table->string('keterangan')->nullable();
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
