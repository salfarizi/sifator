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
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('no_retur')->unique();
            $table->string('nota');
            $table->integer('buyer_id');
            $table->integer('bike_id');
            $table->date('tanggal_jual');
            $table->date('tanggal_retur');
            $table->integer('harga_beli');
            $table->integer('harga_jual')->nullable();
            $table->integer('jumlah_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returs');
    }
};
