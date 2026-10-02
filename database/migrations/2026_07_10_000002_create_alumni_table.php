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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_registrasi')->unique();
            $table->string('nama');
            $table->string('nim')->unique();
            $table->string('nik', 16);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('program_studi');
            $table->string('jurusan');
            $table->integer('tahun_masuk');
            $table->integer('tahun_lulus');
            $table->string('email');
            $table->string('no_hp');
            $table->text('alamat');
            $table->string('status_verifikasi')->default('Belum Diverifikasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
