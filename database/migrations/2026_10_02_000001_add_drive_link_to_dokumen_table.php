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
            $table->text('drive_link')->nullable()->after('pas_foto');
            $table->string('drive_link_status')->default('Menunggu Verifikasi')->after('drive_link');
            $table->text('drive_link_catatan')->nullable()->after('drive_link_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn([
                'drive_link',
                'drive_link_status',
                'drive_link_catatan',
            ]);
        });
    }
};
