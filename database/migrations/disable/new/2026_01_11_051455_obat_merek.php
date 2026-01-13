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
        Schema::create('obat_merek', function (Blueprint $table) {
            $table->string('id_obat_merek', 5)->primary();
            $table->string('id_obat', 5);
            $table->string('id_merek', 5);
            $table->float('harga', 7);
            $table->timestamps();

            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
            $table->foreign('id_merek')->references('id_merek')->on('merek')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_merek');
    }
};
