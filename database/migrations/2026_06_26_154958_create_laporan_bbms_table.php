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
            $table->foreignId('id_permohonan')->constrained('permohonan_bbms')->onDelete('cascade');
            $table->foreignId('id_admin')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_driver')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_kendaraan')->constrained('kendaraans')->onDelete('cascade');
            $table->string('no_polisi');
            $table->foreignId('id_jenis_bbm')->constrained('jenisbbms')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('jumlah_liter', 10, 2);
            $table->decimal('harga_per_liter', 10, 2);
            $table->decimal('total_biaya', 15, 2);
            $table->text('keterangan_admin')->nullable();
            $table->text('catatan_driver')->nullable();
            $table->string('foto_driver')->nullable();
            $table->integer('odometer');
            $table->string('nama_driver')->nullable();
            $table->string('nama_admin')->nullable();
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
