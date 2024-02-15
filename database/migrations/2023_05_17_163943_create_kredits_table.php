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
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('nota');
            $table->integer('buyer_id');
            $table->integer('bike_id');
            $table->date('tanggal_jual');
            $table->integer('harga_beli');
            $table->integer('dp');
            $table->integer('otr_leasing');
            $table->integer('dp_po');
            $table->integer('pencairan');
            $table->integer('angsuran');
            $table->integer('tenor');
            $table->integer('komisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredits');
    }
};
