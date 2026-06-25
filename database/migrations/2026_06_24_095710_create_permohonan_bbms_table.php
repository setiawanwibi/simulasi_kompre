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
        Schema::create('permohonan_bbms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kendaraan')->constrained('kendaraans')->onDelete('cascade');
            $table->foreignId('id_jenis_bbm')->constrained('jenis_bbms')->onDelete('cascade');
            $table->date('tanggal_permohonan');
            $table->integer('jumlah_liter');
            $table->string('foto_sisa_bbm');
            $table->integer('odometer');
            $table->integer('odometer_sebelumnya');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan_admin')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_bbms');
    }
};
