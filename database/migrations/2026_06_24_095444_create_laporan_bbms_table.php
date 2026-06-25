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
        Schema::create('laporan_bbms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_admin');
            $table->unsignedBigInteger('id_driver');
            $table->unsignedBigInteger('id_kendaraan');
            $table->string('no_polisi');
            $table->unsignedBigInteger('id_jenis_bbm');
            $table->date('tanggal');
            $table->decimal('jumlah_liter', 10, 2);
            $table->decimal('harga_per_liter', 10, 2);
            $table->decimal('total_biaya', 10, 2);
            $table->text('keterangan_admin')->nullable();
            $table->text('catatan_driver')->nullable();
            $table->string('foto_driver')->nullable();
            $table->integer('odometer');
            $table->string('nama_driver');
            $table->string('nama_admin');
            $table->string('nama_jenis_bbm');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_bbms');
    }
};
