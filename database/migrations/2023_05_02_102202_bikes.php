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
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->integer('consumer_id');
            $table->string('no_polisi');
            $table->string('merek');
            $table->string('type');
            $table->string('warna');
            $table->integer('tahun_pembuatan');
            $table->string('no_rangka');
            $table->string('no_mesin');
            $table->string('bpkb');
            $table->string('alamat_bpkb');
            $table->string('nama_bpkb');
            $table->date('berlaku_sampai');
            $table->date('perpanjang_stnk');
            $table->string('status');
            $table->string('photo_bpkb')->nullable();
            $table->string('photo_stnk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
