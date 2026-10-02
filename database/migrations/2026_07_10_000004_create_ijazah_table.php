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
        Schema::create('ijazah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('status')->default('Belum Siap'); // 'Belum Siap', 'Siap Diambil', 'Sudah Diambil'
            $table->date('tanggal_siap')->nullable();
            $table->date('tanggal_diambil')->nullable();
            $table->string('lokasi_pengambilan')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ijazah');
    }
};
