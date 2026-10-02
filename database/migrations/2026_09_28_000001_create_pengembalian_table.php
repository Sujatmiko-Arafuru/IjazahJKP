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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');

            // 3 Menu Wajib Pengembalian / Pengambilan
            $table->string('pengambilan_ijazah_file')->nullable();
            $table->string('pengambilan_ijazah_status')->default('Belum Upload'); // Belum Upload, Menunggu Verifikasi, Diverifikasi, Ditolak
            $table->text('pengambilan_ijazah_catatan')->nullable();

            $table->string('pengembalian_toga_file')->nullable();
            $table->string('pengembalian_toga_status')->default('Belum Upload');
            $table->text('pengembalian_toga_catatan')->nullable();

            $table->string('bank_ijazah_file')->nullable();
            $table->string('bank_ijazah_status')->default('Belum Upload');
            $table->text('bank_ijazah_catatan')->nullable();

            // 6 Menu Verifikasi Dokumen Kelulusan
            $table->string('foto_ijazah_file')->nullable();
            $table->string('foto_ijazah_status')->default('Belum Upload');
            $table->text('foto_ijazah_catatan')->nullable();

            $table->string('bukti_transkrip_file')->nullable();
            $table->string('bukti_transkrip_status')->default('Belum Upload');
            $table->text('bukti_transkrip_catatan')->nullable();

            $table->string('sertifikat_profesi_file')->nullable();
            $table->string('sertifikat_profesi_status')->default('Belum Upload');
            $table->text('sertifikat_profesi_catatan')->nullable();

            $table->string('skpi_file')->nullable();
            $table->string('skpi_status')->default('Belum Upload');
            $table->text('skpi_catatan')->nullable();

            $table->string('foto_wisuda_file')->nullable();
            $table->string('foto_wisuda_status')->default('Belum Upload');
            $table->text('foto_wisuda_catatan')->nullable();

            $table->string('kartu_ikapode_file')->nullable();
            $table->string('kartu_ikapode_status')->default('Belum Upload');
            $table->text('kartu_ikapode_catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
