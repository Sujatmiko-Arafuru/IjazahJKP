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
            $table->string('berkas_persyaratan')->nullable()->after('pas_foto');
            $table->string('berkas_persyaratan_status')->default('Menunggu Verifikasi')->after('berkas_persyaratan');
            $table->text('berkas_persyaratan_catatan')->nullable()->after('berkas_persyaratan_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn([
                'berkas_persyaratan',
                'berkas_persyaratan_status',
                'berkas_persyaratan_catatan',
            ]);
        });
    }
};
