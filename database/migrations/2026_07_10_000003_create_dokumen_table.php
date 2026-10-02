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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('pas_foto');
            $table->string('surat_bebas_pustaka');
            $table->string('tracer_kemenkes');
            $table->string('tracer_poltekkes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
