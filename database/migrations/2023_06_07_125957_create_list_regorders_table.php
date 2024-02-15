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
        Schema::create('list_regorders', function (Blueprint $table) {
            $table->id();
            $table->uuid('unique')->unique();
            $table->string('regorder_id');
            $table->string('nama_dealer');
            $table->string('cmo');
            $table->string('pic');
            $table->string('jenis_transaksi');
            $table->string('via');
            $table->string('merk');
            $table->string('type');
            $table->string('tahun_pembuatan');
            $table->integer('otr');
            $table->integer('dp_po');
            $table->integer('pencairan');
            $table->integer('dp');
            $table->integer('angsuran');
            $table->integer('tenor');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_regorders');
    }
};
