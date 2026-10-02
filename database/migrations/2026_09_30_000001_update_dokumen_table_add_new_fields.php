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
        Schema::table('dokumen', function (Blueprint $table) {
            $table->string('tracer_study')->nullable()->after('pas_foto');
            $table->string('keabsahan_data')->nullable()->after('surat_bebas_pustaka');
            $table->string('pengembalian_toga')->nullable()->after('keabsahan_data');
            $table->string('bank_ijazah')->nullable()->after('pengembalian_toga');

            // Verification status & catatan for each required document
            $table->string('pas_foto_status')->default('Menunggu Verifikasi')->after('bank_ijazah');
            $table->text('pas_foto_catatan')->nullable()->after('pas_foto_status');

            $table->string('tracer_study_status')->default('Menunggu Verifikasi')->after('pas_foto_catatan');
            $table->text('tracer_study_catatan')->nullable()->after('tracer_study_status');

            $table->string('surat_bebas_pustaka_status')->default('Menunggu Verifikasi')->after('tracer_study_catatan');
            $table->text('surat_bebas_pustaka_catatan')->nullable()->after('surat_bebas_pustaka_status');

            $table->string('keabsahan_data_status')->default('Menunggu Verifikasi')->after('surat_bebas_pustaka_catatan');
            $table->text('keabsahan_data_catatan')->nullable()->after('keabsahan_data_status');

            $table->string('pengembalian_toga_status')->default('Menunggu Verifikasi')->after('keabsahan_data_catatan');
            $table->text('pengembalian_toga_catatan')->nullable()->after('pengembalian_toga_status');

            $table->string('bank_ijazah_status')->default('Menunggu Verifikasi')->after('pengembalian_toga_catatan');
            $table->text('bank_ijazah_catatan')->nullable()->after('bank_ijazah_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn([
                'tracer_study',
                'keabsahan_data',
                'pengembalian_toga',
                'bank_ijazah',
                'pas_foto_status',
                'pas_foto_catatan',
                'tracer_study_status',
                'tracer_study_catatan',
                'surat_bebas_pustaka_status',
                'surat_bebas_pustaka_catatan',
                'keabsahan_data_status',
                'keabsahan_data_catatan',
                'pengembalian_toga_status',
                'pengembalian_toga_catatan',
                'bank_ijazah_status',
                'bank_ijazah_catatan',
            ]);
        });
    }
};
