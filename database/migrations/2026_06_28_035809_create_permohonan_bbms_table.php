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
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_kendaraan');
            $table->unsignedBigInteger('id_jenis_bbm');
            $table->date('tanggal_permohonan');
            $table->decimal('jumlah_liter', 8, 2);
            $table->string('foto_sisa_bbm');
            $table->integer('odometer');
            $table->integer('odometer_sebelumnya');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default;
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

